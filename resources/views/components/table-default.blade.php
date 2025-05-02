<div class="mt-3.5 overflow-x-auto">
    <table class="table table-xs table-pin-rows table-pin-cols">
        {{ $slot }}
    </table>
</div>

<style>
    /* Estilos Mobile */
    @media (max-width: 1024px) {
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 1rem;
        }
        .table thead {
            display: none;
        }
        .table tbody {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .table tbody tr {
            display: block;
            background: #181c23;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            padding: 0.5rem 1rem;
            /* max-width: 350px; */
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        .table tbody td {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border: none;
            font-size: 0.85rem;
            color: #fff;
        }
        .table tbody td::before {
            content: attr(data-label) ": ";
            font-weight: bold;
            /* min-width: 110px; */
            display: inline-block;
            color: #b3b3b3;
            margin-right: 1rem;
            text-align: left;
        }
        .table tbody td span, .table tbody td a:not(.btn) {
            max-width: 160px;
            word-break: break-word;
            white-space: normal;
            text-align: right;
            /* display: block; */
        }
        .table tbody td:last-child {
            border-top: 1px solid #333;
            margin-top: 0.5rem;
            padding-top: 1rem;
        }
    }
</style>