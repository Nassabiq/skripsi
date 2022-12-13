<?php

namespace App\Http\Controllers;

use App\Models\DetailStokMasuk;
use App\Models\DetailTransaksi;
use App\Models\SKU;
use ArrayIterator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class AnalisisHPPController extends Controller
{

    public function analisis(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $sku = SKU::where('id_produk', $request->id_produk)->where('id_bahan_baku', $request->id_bahan_baku)->first();
        $produksi = DetailTransaksi::with('transaksi')->whereHas('transaksi', function ($q) use ($from, $to) {
            $q->where('status_pesanan', 6);
            $q->orderBy('tgl_transaksi', 'asc');
            $q->whereBetween('tgl_transaksi', [$from, $to]);
        })->where('id_sku', $sku->id_sku)->get();

        $pembelian = DetailStokMasuk::with('stokMasuk')->whereHas('stokMasuk', function ($q) use ($from, $to) {
            $q->orderBy('tgl_stok_masuk', 'asc');
            $q->whereBetween('tgl_stok_masuk', [$from, $to]);
        })->where('id_bahan_baku', $request->id_bahan_baku)->get();

        $data_produksi = [];
        $data_pembelian = [];

        foreach ($produksi as $data) {
            $ukuran = is_null($data->ukuran) ? null : json_decode($data->ukuran);
            array_push(
                $data_produksi,
                [
                    'id' => 'produksi',
                    'tgl' => $data->transaksi->tgl_transaksi,
                    'qty' => is_null($data->qty_produk) ? $data->qty_produk : $data->qty_produk * ($ukuran->panjang * $ukuran->lebar),
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
                    'harga' => $data->harga_beli,
                    'total' => $data->harga_beli * $data->qty_stok,
                ]
            );
        }
        $hpp_data = array_merge($data_produksi, $data_pembelian);
        usort($hpp_data, fn ($a, $b) => strtotime($a["tgl"]) - strtotime($b["tgl"]));

        $last_month_stock_data = (object) array(
            "qty" => 100,
            "harga" => 6000,
        );
        $result = json_encode($hpp_data);

        // return response()->json($this->hpp_data, 200);
        // $hpp_data;
        $this->main(json_decode($result), $last_month_stock_data);
    }

    public function getBahanBaku(Request $request)
    {
        $data = SKU::where('id_produk', $request->id_produk)->with('bahanBaku')->get();
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

    public function generate_row_item($qty, $costperunit)
    {
        return (object) array(
            "qty" => $qty,
            "harga" => $costperunit,
            "jumlah" => ($qty * $costperunit),
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
    public function find_remaining_stock($row_stock, $index_row_stock, $object_iterator, $transaction_item, $current_stock_key, $remaining_stock_needed)
    {
        $remaining_stock_needed = ($row_stock[$index_row_stock]->$current_stock_key->qty - $remaining_stock_needed);

        if ($remaining_stock_needed < 0) {
            # delete the first top stock
            unset($row_stock[$index_row_stock]->$current_stock_key);
            $current_stock_key = $object_iterator->key();
            $remaining_stock_needed *= -1;

            # call itself to check another stock
            $this->find_remaining_stock($row_stock, $index_row_stock, $object_iterator, $transaction_item, $current_stock_key, $remaining_stock_needed);
        }

        return array($current_stock_key, $remaining_stock_needed);
    }
    public function calculate_remaining_stock_on_specific_key($row_stock, $index, $current_stock_key, $remaining_stock_needed)
    {
        $row_stock[$index]->$current_stock_key->qty = $row_stock[$index]->$current_stock_key->qty - $remaining_stock_needed;
        $row_stock[$index]->$current_stock_key->jumlah = $row_stock[$index]->$current_stock_key->harga * $row_stock[$index]->$current_stock_key->qty;
    }
    public function reduce_stock($row_stock, $index, $transaction_item)
    {
        $object_iterator = new ArrayIterator($row_stock[$index]);
        $first_in_stock_key = $object_iterator->key();

        # check if the stock in transaction_item is exceed the current stock at $row_stock[$index]->$first_in_stock_key
        if ($row_stock[$index]->$first_in_stock_key->qty > $transaction_item->qty) {
            # get $remaining_stock_needed from $transaction_item->qty
            $this->calculate_remaining_stock_on_specific_key($row_stock, $index, $first_in_stock_key, $transaction_item->qty);
        } else {
            // check if how much needed for the stock, need to check that the stock is enough for all code
            list($current_stock_key, $remaining_stock_needed) = $this->find_remaining_stock($row_stock, $index, $object_iterator, $transaction_item, $first_in_stock_key, $transaction_item->qty);

            $this->calculate_remaining_stock_on_specific_key($row_stock, $index, $current_stock_key, $remaining_stock_needed);
        }

        return $row_stock;
    }

    public function create_row($result_data, $month, $pembelian, $hpp, $stock)
    {
        array_push($result_data, array(
            "tanggal" => $month,
            "pembelian" => $this->generate_row_item($pembelian ? $pembelian->qty : 0, $pembelian ? $pembelian->harga : 0),
            "hpp" => $this->generate_row_item($hpp ? $hpp->qty : 0, (isset($hpp->harga)) ? $hpp->harga : 0),
            "persediaan" => $stock,
        ));

        return $result_data;
    }

    public function add_row_table_below($row_stock, $result_data, $month, $index, $transaction_item, $costperunit)
    {
        if ($transaction_item->id === "pembelian") {
            $row_stock = $this->add_stock($row_stock, $index, $transaction_item, $costperunit);

            $result_data = $this->create_row(
                $result_data,
                ($month ? $month : ""),
                $transaction_item,
                null,
                $row_stock[$index]
            );
        } else {
            // if transaction_item->id === "produksi"
            $this->reduce_stock($row_stock, $index, $transaction_item);

            $result_data = $this->create_row(
                $result_data,
                ($month ? $month : ""),
                null,
                $transaction_item,
                $row_stock[$index]
            );
        }

        return array($row_stock, $result_data);
    }

    // ---------------------- MAIN
    public function main($transaction_data, $last_month_stock_data)
    {
        $flag_transaction_date = new DateTime($transaction_data[0]->tgl);
        $month_of_transaction = $this->get_full_month_name($flag_transaction_date);

        // create the first $row_stock
        $row_stock = array(
            "first" => (object) array(
                $last_month_stock_data->harga => $this->generate_row_item(
                    $last_month_stock_data->qty,
                    $last_month_stock_data->harga,
                ),
            ),
        );

        // create the first row
        $result_data = $this->create_row(array(), "$month_of_transaction 1", null, null, $row_stock["first"]);

        // prepare $row_stock for foreach calculation
        $row_stock = $this->push_array_and_unlink_the_source($row_stock, $row_stock["first"]);

        foreach ($transaction_data as $index => $transaction_item) {
            $current_transaction_date = new DateTime($transaction_item->tgl);

            list($row_stock, $result_data) = $this->add_row_table_below(
                $row_stock,
                $result_data,
                (($current_transaction_date->format("d") === $flag_transaction_date->format("d") && $index !== 0) ? "" : $current_transaction_date->format("d")),
                $index,
                $transaction_item,
                (isset($transaction_item->harga)) ? $transaction_item->harga : ""
            );

            // prepare $row_stock for next foreach calculation
            $row_stock = $this->push_array_and_unlink_the_source($row_stock, $row_stock[($index)]);

            // set the flag transation date for the next foreach calculation
            $flag_transaction_date = $current_transaction_date;
        }

        // the calculation is saved to $result_data
        print_r($result_data);
        // return response()->json($result_data, 200);
        // $result = array_values($result_data);
        // return response()->json(json_encode($result, true), 200);
    }

    // Everything starts from here?!
}
