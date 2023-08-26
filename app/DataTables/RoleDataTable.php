<?php

namespace App\DataTables;

use App\Models\Role;
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

class RoleDataTable extends DataTable
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
        ->addColumn('permission', function($row){
            $data = '';
            if (count($row->permissions) == count(Helper::permissions())){
                $data .= '<span class="badge bg-primary">All Permissions</span>';
            }else{
                foreach ($row->permissions as $p){
                    $data .= '<span class="badge bg-secondary mb-1">'.$p->name.'</span> ';
                }
            }
            return $data;
        })
        ->addColumn('action', function($row){
            $action = '';
            if(Gate::allows('edit role')){
                $action .= '<a href="#" onclick="editData(' . $row->id . ')" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Edit Data">
                    <i class="bi bi-pencil-square"></i>
                </a>';
            }
            if(Gate::allows('delete role')){
                $action .= '<form method="post" action="' . route("roles.destroy", Crypt::encrypt($row->id)) . '"
                    id="deleteRole" style="display:inline" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Hapus Data">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>';
            }
            return $action;
        })
        ->rawColumns(['permission','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
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
                    ->setTableId('role-table')
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
            Column::make('name'),
            Column::computed('permission'),
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
        return 'Role_' . date('YmdHis');
    }
}