<?php

namespace App\Http\Controllers;

use App\Models\DetailStokMasuk;
use App\Models\DetailTransaksi;
use App\Models\HPP;
use App\Models\SKU;
use ArrayIterator;
use Carbon\Carbon;
use DateTime;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnalisisHPPController extends Controller
{
    public function analisis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_produk'   => 'required',
            'id_bahan_baku'   => 'required',
            'month'   => 'required',
        ], [
            'required' => 'form ini harus diisi'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $month = (int) date('m', strtotime($request->month));
        $year = (int) date('Y', strtotime($request->month));

        $last_month_stock_data_empty = (object) array(
            "p0" => (object) array(
                "qty" => 0,
                "harga" => 0,
                "jumlah" => 0,
            )
        );

        $is_last_stock = true;
        $is_wip = false;

        // LAST MONTH STOCK
        $stock = $this->generateHPPData($request, $month, $year, $last_month_stock_data_empty, $is_last_stock, $is_wip);
        $last_month_stock_data = count($stock[0]) > 0 ? $this->main($stock[0], $stock[1])[1]->persediaan : $stock[1];

        // WORK IN PROCESS STOCK DATA
        $work_in_process_data = $this->generateHPPData($request, $month, $year, $last_month_stock_data_empty, $is_last_stock, $is_wip = true);
        $work_in_process_result = count($work_in_process_data[0]) > 0
            ? $this->main($work_in_process_data[0], $work_in_process_data[1])
            : $work_in_process_data[1];

        // GENERATE HPP DATA
        $hpp = $this->generateHPPData($request, $month, $year, $last_month_stock_data, $is_last_stock = false, $is_wip = false);
        return count($hpp[0]) > 0
            ? response()->json([
                'hpp' => $this->main($hpp[0], $hpp[1]),
                'wip' => $work_in_process_result
            ], 200)
            : response()->json(['error' => 'Tidak Ada Data'], 400);
    }

    public function generateHPPData($request, $month, $year, $last_month_stock_data, $is_last_stock, $is_wip)
    {

        $sku = SKU::where('id_produk', $request->id_produk)->where('id_bahan_baku', $request->id_bahan_baku)->first();
        $date =  $year . '-' . '0' . $month . '-' . '01';

        if ($is_wip == true) {
            # code...
            $produksi = DetailTransaksi::with('transaksi')->whereHas('transaksi', function ($q) use ($date) {
                $q->where('status_pesanan', '<', 7);
                $q->orderBy('tgl_transaksi', 'asc');
            })->where('id_sku', $sku->id_sku)->get();

            // var_dump($produksi);

            $pembelian = DetailStokMasuk::with('stokMasuk')->whereHas('stokMasuk', function ($q) use ($date) {
                $q->orderBy('tgl_stok_masuk', 'asc');
            })->where('id_bahan_baku', $request->id_bahan_baku)->get();
        } else {
            $produksi = DetailTransaksi::with('transaksi')->whereHas(
                'transaksi',
                function ($q) use ($date, $month, $year, $is_last_stock) {
                    $q->where('status_pesanan', 7);
                    $q->orderBy('tgl_transaksi', 'asc');
                    $q->when(
                        $is_last_stock == true,
                        function ($q) use ($date) {
                            return $q->whereDate('tgl_transaksi', '<', $date);
                        },
                        function ($q) use ($month, $year) {
                            $q->whereMonth('tgl_transaksi', $month);
                            $q->whereYear('tgl_transaksi', $year);
                        },
                    );
                }
            )->where('id_sku', $sku->id_sku)->get();

            $pembelian = DetailStokMasuk::with('stokMasuk')->whereHas(
                'stokMasuk',
                function ($q) use ($date, $month, $year, $is_last_stock) {
                    $q->orderBy('tgl_stok_masuk', 'asc');
                    $q->when(
                        $is_last_stock == true,
                        function ($q) use ($date) {
                            $q->whereDate('tgl_stok_masuk', '<', $date);
                        },
                        function ($q) use ($month, $year) {
                            $q->whereMonth('tgl_stok_masuk', $month);
                            $q->whereYear('tgl_stok_masuk', $year);
                        }
                    );
                }
            )->where('id_bahan_baku', $request->id_bahan_baku)->get();
        }

        // GENERATING HPP DATA
        $data_produksi = [];
        $data_pembelian = [];

        foreach ($produksi as $data) {
            $ukuran = is_null($data->ukuran) ? null : json_decode($data->ukuran);
            array_push(
                $data_produksi,
                [
                    'id' => 'produksi',
                    'tgl' => $data->transaksi->tgl_transaksi,
                    'qty' => is_null($data->ukuran) ? $data->qty_produk : $data->qty_produk * ($ukuran->panjang * $ukuran->lebar),
                ]
            );
        }
        foreach ($pembelian as $data) {
            array_push(
                $data_pembelian,
                [
                    'id' => 'pembelian',
                    'tgl' => $data->stokMasuk->tgl_stok_masuk,
                    'qty' => $data->qty_stok,
                    'harga' => round($data->harga_beli / $data->qty_stok),
                    'total' => $data->harga_beli,
                ]
            );
        }
        $hpp_data = array_merge($data_produksi, $data_pembelian);
        usort($hpp_data, fn ($a, $b) => strtotime($a["tgl"]) - strtotime($b["tgl"]));

        $result = json_encode($hpp_data);
        $stock = json_encode($last_month_stock_data);

        $data = [json_decode($result), json_decode($stock)];
        return $data;
    }

    public function getBahanBaku(Request $request)
    {
        $data = SKU::where('id_produk', $request->id_produk)->with('bahanBaku')->get();
        return response()->json($data, 200);
    }

    public function saveResult(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'biaya_produksi'   => 'required|numeric',
            'overhead.mesin'   => 'required|numeric',
            'overhead.produksi_tambahan'   => 'required|numeric',
            'overhead.tenaga_kerja'   => 'required|numeric',
            'overhead.maintenance'   => 'required|numeric',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $sku = SKU::where('id_bahan_baku', $request->id_bahan_baku)->where('id_produk', $request->id_produk)->first();

        $id_hpp = IdGenerator::generate(['table' => 'hpp', 'field' => 'id_hpp', 'length' => 9, 'prefix' => 'HPP-']);
        $data = HPP::create([
            'id_hpp' => $id_hpp,
            'id_sku' => $sku->id_sku,
            'biaya_overhead' => json_encode($request->overhead),
            'tgl_analisa' => Carbon::now(),
            'nilai_hpp' => $request->hpp,
            'jml_penjualan' => $request->jml_penjualan,
        ]);

        return response()->json($data, 200);
    }

    public function get_full_month_name($date)
    {
        try {
            return ($date->format("F"));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(1);
        }
    }

    public function generate_row_item($qty, $harga)
    {
        return (object) array(
            "qty" => $qty,
            "harga" => $harga,
            "jumlah" => ($qty * $harga),
        );
    }

    // merge the array and unlink the array added from the soure by using json string encoder and decoder
    public function push_array_and_unlink_the_source($array, $array_tobe_added)
    {
        array_push($array, json_decode(json_encode($array_tobe_added)));

        return $array;
    }

    // configure the stock
    public function add_stock($row_stock, $index, $transaction_item, $costperunit)
    {
        if (property_exists($row_stock[$index], $costperunit)) {
            $row_stock[$index]->$costperunit->qty = $row_stock[$index]->$costperunit->qty + $transaction_item->qty;
            $row_stock[$index]->$costperunit->jumlah = $row_stock[$index]->$costperunit->harga * $row_stock[$index]->$costperunit->qty;
        } else {
            $row_stock[$index]->$costperunit = $this->generate_row_item(
                $transaction_item->qty,
                $transaction_item->harga,
            );
        }

        return $row_stock;
    }

    /**
     * stock checker to include another stock if the frist_stock not enough
     *
     * @param array $row_stock The row stock array.
     * @param string $index_row_stock The row_stock index key.
     * @param ArrayIterator $object_iterator The iterator for $row_stock.
     * @param array  $transaction_item foreach transaction data item.
     * @param string $current_stock_key  The current of stock key.
     * @param int $remaining_stock_needed The variable to save the remaining_stock to be reduced.
     * */

    public function find_remaining_stock($row_stock, $index_row_stock, $object_iterator, $transaction_item, $current_stock_key, $remaining_stock_needed, $hpp_item, $total_data)
    {
        $remaining_stock_needed = ($row_stock[$index_row_stock]->$current_stock_key->qty - $remaining_stock_needed);

        if ($remaining_stock_needed < 0) {
            # add the stock to the hpp item first
            $hpp_item->$current_stock_key = $this->generate_row_item($row_stock[$index_row_stock]->$current_stock_key->qty, $row_stock[$index_row_stock]->$current_stock_key->harga);

            $total_data->penjualan->qty += $hpp_item->$current_stock_key->qty;
            $total_data->penjualan->jumlah += $hpp_item->$current_stock_key->jumlah;

            # delete the first top stock
            unset($row_stock[$index_row_stock]->$current_stock_key);
            $current_stock_key = $object_iterator->key();
            $remaining_stock_needed *= -1;

            # call itself to check another stock
            $this->find_remaining_stock($row_stock, $index_row_stock, $object_iterator, $transaction_item, $current_stock_key, $remaining_stock_needed, $hpp_item, $total_data);
        } else {
            $hpp_item->$current_stock_key = $this->generate_row_item($remaining_stock_needed, $row_stock[$index_row_stock]->$current_stock_key->harga);
        }

        return array($current_stock_key, $remaining_stock_needed, $hpp_item);
    }
    public function calculate_remaining_stock_on_specific_key($row_stock, $index, $current_stock_key, $remaining_stock_needed, $hpp_item, $total_data)
    {
        $row_stock[$index]->$current_stock_key->qty = $row_stock[$index]->$current_stock_key->qty - $remaining_stock_needed;
        $row_stock[$index]->$current_stock_key->jumlah = $row_stock[$index]->$current_stock_key->harga * $row_stock[$index]->$current_stock_key->qty;

        $hpp_item->$current_stock_key = $this->generate_row_item($remaining_stock_needed, $row_stock[$index]->$current_stock_key->harga);

        $total_data->penjualan->qty += $hpp_item->$current_stock_key->qty;
        $total_data->penjualan->jumlah += $hpp_item->$current_stock_key->jumlah;
    }
    public function reduce_stock($row_stock, $index, $transaction_item, $hpp_item, $total_data)
    {
        $object_iterator = new ArrayIterator($row_stock[$index]);
        $first_in_stock_key = $object_iterator->key();

        # check if the stock in transaction_item is exceed the current stock at $row_stock[$index]->$first_in_stock_key
        if ($row_stock[$index]->$first_in_stock_key->qty > $transaction_item->qty) {
            # get $remaining_stock_needed from $transaction_item->qty
            $this->calculate_remaining_stock_on_specific_key($row_stock, $index, $first_in_stock_key, $transaction_item->qty, $hpp_item, $total_data);
        } else {
            // check if how much needed for the stock, need to check that the stock is enough for all code
            list($current_stock_key, $remaining_stock_needed, $hpp_item) = $this->find_remaining_stock($row_stock, $index, $object_iterator, $transaction_item, $first_in_stock_key, $transaction_item->qty, $hpp_item, $total_data);

            $this->calculate_remaining_stock_on_specific_key($row_stock, $index, $current_stock_key, $remaining_stock_needed, $hpp_item, $total_data);
        }

        return array(
            $row_stock[$index],
            $hpp_item
        );
    }

    public function create_row($result_data, $month, $pembelian, $hpp, $stock)
    {
        array_push($result_data, array(
            "tanggal" => $month,
            "pembelian" => $this->generate_row_item($pembelian ? $pembelian->qty : 0, $pembelian ? $pembelian->harga : 0),
            "hpp" => ($hpp ? $hpp : $this->generate_row_item(0, 0)),
            "persediaan" => $stock,
        ));

        return $result_data;
    }

    public function add_row_table_below($row_stock, $result_data, $month, $index, $transaction_item, $costperunit, $total_data)
    {
        if ($transaction_item->id === "pembelian") {
            $row_stock = $this->add_stock($row_stock, $index, $transaction_item, ("p" . $costperunit));

            $result_data = $this->create_row(
                $result_data,
                ($month ? $month : ""),
                $transaction_item,
                null,
                $row_stock[$index]
            );

            $total_data->pembelian->qty += $transaction_item->qty;
            $total_data->pembelian->jumlah += ($transaction_item->qty * $transaction_item->harga);
        } else {
            // if transaction_item->id === "produksi"
            # create an array to save hpp transaction item
            $hpp_item = (object) array();
            list($reduced_stock) = $this->reduce_stock($row_stock, $index, $transaction_item, $hpp_item, $total_data);

            $result_data = $this->create_row(
                $result_data,
                ($month ? $month : ""),
                null,
                $hpp_item,
                $reduced_stock
            );
        }

        return array($row_stock, $result_data);
    }

    // ---------------------- MAIN
    public function main($transaction_data, $last_month_stock_data)
    {
        $flag_transaction_date = new DateTime($transaction_data[0]->tgl);
        $month_of_transaction = $this->get_full_month_name($flag_transaction_date);

        // create the first row
        $result_data = $this->create_row(array(), "$month_of_transaction 1", null, null, json_decode(json_encode($last_month_stock_data)));

        // prepare $row_stock for foreach calculation
        $row_stock = array($last_month_stock_data);

        // print_r($row_stock);exit(1);

        // prepare the total variable
        $total_data = (object) array(
            "pembelian" => (object) array(
                "qty" => 0,
                "jumlah" => 0,
            ),
            "penjualan" => (object) array(
                "qty" => 0,
                "jumlah" => 0,
            )
        );

        foreach ($transaction_data as $index => $transaction_item) {
            $current_transaction_date = new DateTime($transaction_item->tgl);

            list($row_stock, $result_data) = $this->add_row_table_below(
                $row_stock,
                $result_data,
                (($current_transaction_date->format("d") === $flag_transaction_date->format("d") && $index !== 0) ? "" : $current_transaction_date->format("d")),
                $index,
                $transaction_item,
                (isset($transaction_item->harga)) ? $transaction_item->harga : "",
                $total_data
            );

            // prepare $row_stock for next foreach calculation
            $row_stock = $this->push_array_and_unlink_the_source($row_stock, $row_stock[$index]);

            // set the flag transation date for the next foreach calculation
            $flag_transaction_date = $current_transaction_date;
        }

        $total_data->persediaan = $result_data[array_key_last($result_data)]["persediaan"];
        $total_data->barang_yang_tersedia_dijual = $total_data->pembelian->jumlah + $total_data->penjualan->jumlah;

        // the calculation is saved to $result_data
        // echo "RESULT Data -----------------------------------\n\n";
        // print_r($result_data);

        // echo "\n\nTotal Data -----------------------------------\n\n";
        // print_r($total_data);
        $data = [$result_data, $total_data];
        return list($result_data, $total_data) = $data;
    }

    // Everything starts from here?!
}
