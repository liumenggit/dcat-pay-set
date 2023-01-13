<style>
    .mailbox-attachment-icon {
        max-height: none;
    }

    .mailbox-attachment-info {
        background: #fff;
    }

    .mailbox-attachments .img-thumbnail {
        border: 1px solid #fff;
        border-radius: 0;
        background-color: #fff;
    }

    .mailbox-attachment-icon.has-img > img {
        max-width: 100% !important;
        /*height: 180px!important;*/
    }

    .mailbox-attachment-info .item {
        margin-bottom: 6px;
    }

    .mailbox-attachments li {
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .08);
        border: 0;
        background: #fff;
    }

    body.dark-mode .table {
        background-color: #2c2c43;
    }

    body.dark-mode .mailbox-attachments .img-thumbnail {
        border-color: #223;
        background-color: #223;
    }

    body.dark-mode .mailbox-attachment-info {
        background: #223;
    }

    body.dark-mode .mailbox-attachments li {
        border-color: #223;
        background-color: #223;
    }

    body.dark-mode .mailbox-attachment-name {
        color: #a8a9bb
    }

    table.tftable {
        font-size: 12px;
        color: #333333;
        width: 100%;
        border-width: 1px;
        border-color: #f2f2f2;
        border-collapse: collapse;
    }

    table.tftable th {
        font-size: 12px;
        background-color: #f9f9fc;
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #f2f2f2;
        text-align: left;
    }

    table.tftable tr {
        background-color: #ffffff;
    }

    table.tftable td {
        font-size: 12px;
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #f2f2f2;
    }
</style>
<script type="text/javascript">
    window.onload = function () {
        var tfrow = document.getElementById('tfhover').rows.length;
        var tbRow = [];
        for (var i = 1; i < tfrow; i++) {
            tbRow[i] = document.getElementById('tfhover').rows[i];
            tbRow[i].onmouseover = function () {
                this.style.backgroundColor = '#f3f8aa';
            };
            tbRow[i].onmouseout = function () {
                this.style.backgroundColor = '#ffffff';
            };
        }
    };
</script>

<div class="dcat-box custom-data-table dt-bootstrap4">

    {{--    @include('admin::grid.table-toolbar')--}}

    {!! $grid->renderFilter() !!}

    {!! $grid->renderHeader() !!}

    <div class="table-responsive table-wrapper mt-1">
        <ul class="mailbox-attachments clearfix {{ $grid->formatTableClass() }} p-0" id="{{ $tableId }}">
            <!-- Row Highlight Javascript -->

            <table id="tfhover" class="tftable" border="1">
                <tr>
                    <th rowspan="5">
                        <a>微信公众号</a>

                    </th>
                    <th>支付方式</th>
                    <th>模板名称</th>
                    <th>状态</th>
                    <th>其他</th>
                </tr>
                <tr>
                    <td>
                        <div style="display: flex" align="center" valign="center">
                            <a>11</a>
                            <a>asdasdasd</a>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <select style="width: 100%;" class="grid-column-select select2-hidden-accessible"
                                    data-reload="" data-url="http://103.39.211.179:8080/admin/components/grid/1"
                                    data-name="select" data-select2-id="select2-data-1-g1uo" tabindex="-1"
                                    aria-hidden="true">
                            </select>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>支付宝支付</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>余额支付</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>线下转账</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            @foreach($grid->rows() as $row)
                <li>
                    <div class="mailbox-attachment-info">
                        <div class="d-flex justify-content-between item">
                            <span
                                class="mailbox-attachment-name">{!! $grid->columns()->get('name')->getLabel() !!}</span> {!! $row->column('name') !!}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{--    {!! $grid->renderFooter() !!}--}}

    {{--    @include('admin::grid.table-pagination')--}}

</div>
