<?php

namespace App\DataTables;

use App\Models\Agent;
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

class AgentDataTable extends DataTable
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
                $img = '<a data-lightbox="'.Helper::getFilename($row->image).'" href="'.asset('').'storage/agent/'.Helper::getFilename($row->image).'"><img src="'.asset('').'storage/agent/'.Helper::getFilename($row->image).'" width="50" height="50"></a>';
            }
            return $img;
        })
        ->addColumn('fullname',function($row){
            return $row->token.'<hr class="p-0 my-1 mx-0">'.$row->fullname;
        })
        ->addColumn('ttl',function($row){
            return $row->pob.', '.Helper::dateIndo($row->dob).'<hr class="p-0 my-1 mx-0">'.$row->phone;
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
            if(Gate::allows('edit agent')){
                $action .= '<a href="' . route("agents.edit", Crypt::encrypt($row->id)) . '" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Edit Data">
                    <i class="bi bi-pencil-square"></i>
                </a>';
            }
            if(Gate::allows('delete agent')){
            $action .= '<form method="post" action="' . route("agents.destroy", Crypt::encrypt($row->id)) . '"
                id="deleteAgent" style="display:inline" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Hapus Data">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                    <i class="bi bi-trash"></i>
                </button>
            </form>';
            }
            return $action;
        })
        ->rawColumns(['image','fullname','ttl','status','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Agent $model): QueryBuilder
    {
        // return $model->newQuery();
        $query = $model->query()->orderBy('id','desc');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('agent-table')
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
            Column::make('image'),
            // Column::make('token'),
            Column::make('fullname')->title('Token <hr class="p-0 m-0"> Nama Agent'),
            Column::make('ttl')->title('Tempat, Tanggal Lahir <hr class="p-0 m-0"> Nomor HP'),
            // Column::make('phone')->title('HP'),
            Column::make('address')->title('Alamat'),
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
        return 'Agent_' . date('YmdHis');
    }
}