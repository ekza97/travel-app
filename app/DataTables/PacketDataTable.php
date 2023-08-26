<?php

namespace App\DataTables;

use App\Models\Packet;
use App\Helpers\Helper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PacketDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addIndexColumn()
        ->addColumn('image',function($row){
            if($row->image==null){
                $img = '<a data-lightbox="no-image.jpeg" href="'.asset('no-image.jpeg').'"><img src="'.asset('no-image.jpeg').'" width="50" height="50"></a>';
            }else{
                $img = '<a data-lightbox="'.Helper::getFilename($row->image).'" href="'.asset('').'storage/packet/'.Helper::getFilename($row->image).'"><img src="'.asset('').'storage/packet/'.Helper::getFilename($row->image).'" width="50" height="50"></a>';
            }
            return $img;
        })
        ->addColumn('category_id',function($row){
            return $row->categories->name;
        })
        ->addColumn('airplane_id',function($row){
            return $row->airplanes->name;
        })
        ->addColumn('hotel',function($row){
            return $row->mekkah_hotels->name.'<hr class="p-0 my-1 mx-0">'.$row->madinah_hotels->name;
        })
        ->addColumn('cost',function($row){
            return Helper::money($row->cost);
        })
        ->addColumn('status',function($row){
            $sts = '';
            if($row->is_active==1){
                $sts .= '<span class="badge bg-success">Aktif</span>';
            }else{
                $sts .= '<span class="badge bg-danger">Tidak Aktif</span>';
            }
            return $sts;
        })
        ->addColumn('action', function($row){
            $action = '';
            if(Gate::allows('edit packet')){
                $action .= '<a href="' . route("packets.edit", Crypt::encrypt($row->id)) . '" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Edit Data">
                    <i class="bi bi-pencil-square"></i>
                </a>';
            }
            if(Gate::allows('delete packet')){
            $action .= '<form method="post" action="' . route("packets.destroy", Crypt::encrypt($row->id)) . '"
                id="deletePacket" style="display:inline" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Hapus Data">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                    <i class="bi bi-trash"></i>
                </button>
            </form>';
            }
            return $action;
        })
        ->rawColumns(['image','hotel','status','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Packet $model): QueryBuilder
    {
        // return $model->newQuery();
        $query = $model->query()->with(['categories','airplanes','mekkah_hotels','madinah_hotels'])->orderBy('id','desc');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('packet-table')
                    ->addTableClass('table-hover')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->width(20)->searchable(false)->orderable(false),
            Column::make('image')->title('Gambar'),
            Column::make('title')->title('Nama Paket'),
            Column::make('category_id')->title('Kategori'),
            Column::make('airplane_id')->title('Pesawat'),
            Column::make('hotel')->title('Hotel Mekkah <hr class="p-0 m-0">Hotel Madinah'),
            Column::make('cost')->title('Harga'),
            Column::make('status')->title('Aktif?'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Packet_' . date('YmdHis');
    }
}