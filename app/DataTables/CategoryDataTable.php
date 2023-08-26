<?php

namespace App\DataTables;

use App\Models\Category;
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

class CategoryDataTable extends DataTable
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
        ->addColumn('action', function($row){
            $action = '';
            if(Gate::allows('edit category')){
                $action .= '<a href="#" onclick="editData(' . $row->id . ')" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Edit Data">
                    <i class="bi bi-pencil-square"></i>
                </a>';
            }
            if(Gate::allows('delete category')){
            $action .= '<form method="post" action="' . route("categories.destroy", Crypt::encrypt($row->id)) . '"
                id="deleteCategory" style="display:inline" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Hapus Data">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-outline-danger btn-sm btn-icon">
                    <i class="bi bi-trash"></i>
                </button>
            </form>';
            }
            return $action;
        })
        ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Category $model): QueryBuilder
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
                    ->setTableId('category-table')
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
            Column::make('name')->title('Kategori'),
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
        return 'Category_' . date('YmdHis');
    }
}