<?php

namespace App\DataTables;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn() // Add DT_RowIndex for counter
            ->editColumn('photo_path', function ($report) {
                if (!$report->photo_path) {
                    return '-';
                }

                $url = asset($report->photo_path);

                return '<img src="' . $url . '" alt="Image" width="60" height="60" style="object-fit: cover; border-radius: 5px;">';
            })
            ->editColumn('narrative', function ($report) {
                // Limit description to 5 words with "..."
                $words = explode(' ', $report->narrative);
                if (count($words) > 5) {
                    return implode(' ', array_slice($words, 0, 5)) . '...';
                }
                return $report->narrative;
            })
            ->editColumn('created_at', fn($report) => Carbon::parse($report->created_at)->format('M d, Y'))
            ->editColumn('updated_at', fn($report) => $report->updated_at ? Carbon::parse($report->updated_at)->format('d-M-Y') : '-')

            // // Tell Yajra how to sort these formatted columns
            ->orderColumn('created_at', 'reports.created_at $1')
            ->orderColumn('updated_at', 'reports.updated_at $1')

            ->addColumn('action', fn($report) => view('frontend.pages._actions', compact('report'))->render())
            ->rawColumns(['action', 'photo_path'])
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(Report $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('status', 'approved')
            ->orderBy('reviewed_at', 'desc'); // Show newly approved reports first
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reports-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->processing(false) // Disable processing loader
            ->serverSide(true)
            ->orderBy(3, 'desc') // Order by location column
            ->pageLength(20) // Show 20 records per page
            ->responsive(false) // Disable responsive mode (no + icon)
            ->autoWidth(false)
            ->addTableClass('table align-middle')
            ->parameters([
                'dom' => '<"row"<"col-sm-12"tr>><"row"<"col-sm-12 text-end"p>>', // Custom DOM with right-aligned pagination
                'scrollX' => true, // Enable horizontal scrolling
                'language' => [
                    'info' => '', // Hide info text
                    'infoEmpty' => '',
                    'emptyTable' => 'No approved reports available',
                    'processing' => '', // Remove processing loader
                    'paginate' => [
                        'first' => 'First',
                        'last' => 'Last',
                        'next' => 'Next',
                        'previous' => 'Previous'
                    ]
                ],
                'lengthMenu' => [[20, 50, 100, -1], [20, 50, 100, 'All']]
            ])
            ->drawCallback(
                "function() {
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                    }
                    // Force right align pagination with spacing from border
                    $('.dataTables_paginate').css({'text-align': 'right', 'width': '100%', 'display': 'block', 'padding': '20px 32px 20px 20px'});
                    $('.dt-paging').css({'display': 'inline-block', 'float': 'right', 'margin-right': '20px'});
                }"
            );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('#')
                ->orderable(false)
                ->searchable(false)
                ->width(50)
                ->addClass('text-center'),
            Column::make('photo_path')->title('Photo')->orderable(false),
            Column::make('individual_name')->title('Name'),
            Column::make('location')->title('Location'),
            Column::make('narrative')->title('Description')->orderable(false),
            Column::computed('action')
                ->title('Actions')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Reports_' . date('YmdHis');
    }
}
