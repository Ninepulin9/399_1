@extends('admin.layout')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    svg {
        width: 100%;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6>รายการออเดอร์ทั้งหมด</h6>
                        <hr>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center">สั่งหน้าร้าน</th>
                                    <th class="text-center">เลขโต้ะ</th>
                                    <th class="text-center">ยอดราคา</th>
                                    <th class="text-left">หมายเหตุ</th>
                                    <th class="text-left">วันที่สั่ง</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 order-2">
                <div class="card">
                    <div class="card-header">
                        <h6>รายการชำระเงินแล้ว</h6>
                        <hr>
                    </div>
                    <div class="card-body">
                        <table id="myTable2" class="display table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center">เลขที่ใบเสร็จ</th>
                                    <th class="text-center">โต้ะ</th>
                                    <th class="text-center">ยอดรวมทั้งหมด</th>
                                    <th class="text-center">วันที่ชำระ</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดออเดอร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-html">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-detail-pay">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดออเดอร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-html-pay">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-pay">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ชำระเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body d-flex justify-content-center">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5>ยอดชำระ</h5>
                                <h1 class="text-success" id="totalPay"></h1>
                            </div>
                            <div class="col-12 d-flex justify-content-center mb-3" id="qr_code">
                            </div>
                        </div>
                        <input type="hidden" id="table_id">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirm_pay">ยืนยันชำระเงิน</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-tax-full">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ออกใบกำกับภาษี</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tax-full">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">ชื่อลูกค้า : </label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label for="tel" class="form-label">เบอร์โทรศัพท์ : </label>
                                <input type="text" name="tel" id="tel" class="form-control" required onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10">
                            </div>
                            <div class="col-md-12">
                                <label for="tax_id" class="form-label">เลขประจำตัวผู้เสียภาษี : </label>
                                <input type="text" name="tax_id" id="tax_id" class="form-control" required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="col-md-12">
                                <label for="address" class="form-label">ที่อยู่ : </label>
                                <textarea rows="4" class="form-control" name="address" id="address" required></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="pay_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="preview-tax-full">ออกใบเสร็จ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modalRecipes">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดสูตรอาหาร</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-html-recipes">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
            </div>
</div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="previewLabel" aria-hidden="true" id="modal-preview">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">พรีวิวใบเสร็จ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="" id="preview-frame" style="width:100%;height:500px;border:0;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirm-print">ปริ้นผ่าน Application</button>
                <button type="button" class="btn btn-warning" id="print-browser">ปริ้นแบบธรรมดา</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function sendPrintCommand() {
      if (!data || !data.pay || !data.order) return;
      const payloadArr = [
        {
          align: "center",
          bold: true,
          data: data.config?.name || '',
          size: 2,
          type: "text"
        },
        { type: "newline" },
        {
          align: "left",
          bold: true,
          data: `เลขที่ใบเสร็จ #${data.pay.payment_number || ''}`,
          type: "text"
        },
        {
          align: "left",
          data: `วันที่: ${data.pay.created_at || ''}`,
          type: "text"
        }
      ];
      // ถ้ามี tax_full ให้แสดงข้อมูลลูกค้า
      if (data.tax_full) {
        payloadArr.push(
          { align: "left", data: `ชื่อลูกค้า: ${data.tax_full.name || ''}`, type: "text" },
          { align: "left", data: `เบอร์โทรศัพท์: ${data.tax_full.tel || ''}`, type: "text" },
          { align: "left", data: `เลขประจำตัวผู้เสียภาษี: ${data.tax_full.tax_id || ''}`, type: "text" },
          { align: "left", data: `ที่อยู่: ${data.tax_full.address || ''}`, type: "text" }
        );
      }
      payloadArr.push(
        { type: "newline" },
        { type: "line" },
        ...data.order.flatMap(rs => {
          let arr = [
            {
              columns: [
                { text: rs.menu?.name || '', width: 60 },
                { text: String(rs.quantity), width: 10 },
                { text: `${Number(rs.price).toFixed(2)} ฿`, width: 30 }
              ],
              type: "table"
            }
          ];
          if (rs.option && Array.isArray(rs.option)) {
            arr = arr.concat(rs.option.map(opt => ({
              align: "left",
              data: `+ ${opt.option?.type || ''}`,
              type: "text"
            })));
          }
          arr.push({ type: "line" });
          return arr;
        }),
        { type: "newline" },
        { bold: true, size: 2, type: "line" },
        {
          align: "right",
          bold: true,
          data: `Total: ${Number(data.pay.total).toFixed(2)} ฿`,
          size: 1,
          type: "text"
        },
        { type: "newline" },
        { type: "newline" }
      );
      const payload = {
        command: "PRINT_START",
        payload: payloadArr
      };
      const bridge = getBridge();
      if (bridge) {
        if (bridge.postMessage) bridge.postMessage(JSON.stringify(payload));
        else if (typeof bridge.sendRequest === "function") bridge.sendRequest(JSON.stringify(payload));
      } else {
        alert("JSBridge not available");
      }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script>
    var language = '{{asset("assets/js/datatable-language.js")}}';
    $(document).ready(function() {
        $("#myTable").DataTable({
            language: {
                url: language,
            },
            processing: true,
            scrollX: true,
            order: [
                [4, 'desc']
            ],
            ajax: {
                url: "{{route('ListOrder')}}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            },

            columns: [{
                    data: 'flag_order',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'table_id',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'total',
                    class: 'text-center',
                    width: '10%'
                },
                {
                    data: 'remark',
                    class: 'text-left',
                    width: '15%'
                },
                {
                    data: 'created',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'status',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'action',
                    class: 'text-center',
                    width: '15%',
                    orderable: false
                },
            ]
        });
        $("#myTable2").DataTable({
            language: {
                url: language,
            },
            processing: true,
            scrollX: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: "{{route('ListOrderPay')}}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            },

            columns: [{
                    data: 'payment_number',
                    class: 'text-center',
                    width: '20%'
                },
                {
                    data: 'table_id',
                    class: 'text-center',
                    width: '10%'
                },
                {
                    data: 'total',
                    class: 'text-center',
                    width: '20%'
                },
                {
                    data: 'created',
                    class: 'text-center',
                    width: '20%'
                },
                {
                    data: 'action',
                    class: 'text-center',
                    width: '30%',
                    orderable: false
                },
            ]
        });
    });
</script>
<script>
    $(document).on('click', '.modalShow', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "{{ route('listOrderDetail') }}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-detail').modal('show');
                $('#body-html').html(response);
            }
        });
    });

    $(document).on('click', '.modalShowPay', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "{{ route('listOrderDetailPay') }}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-detail-pay').modal('show');
                $('#body-html-pay').html(response);
            }
        });
    });

    $(document).on('click', '.modalPay', function(e) {
        var total = $(this).data('total');
        var id = $(this).data('id');
        Swal.showLoading();
        $.ajax({
            type: "post",
            url: "{{ route('generateQr') }}",
            data: {
                total: total
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.close();
                $('#modal-pay').modal('show');
                $('#totalPay').html(total + ' บาท');
                $('#qr_code').html(response);
                $('#table_id').val(id);
            }
        });
    });

    $('#confirm_pay').click(function(e) {
        e.preventDefault();
        var id = $('#table_id').val();
        $.ajax({
            url: "{{route('confirm_pay')}}",
            type: "post",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-pay').modal('hide')
                if (response.status == true) {
                    Swal.fire(response.message, "", "success");
                    $('#myTable').DataTable().ajax.reload(null, false);
                    $('#myTable2').DataTable().ajax.reload(null, false);
                } else {
                    Swal.fire(response.message, "", "error");
                }
            }
        });
    });

    $(document).on('click', '.modalTax', function(e) {
        var id = $(this).data('id');
        $('#modal-tax-full').modal('show');
        $('#pay_id').val(id);
    });

    $('#modal-tax-full').on('hidden.bs.modal', function() {
        $('#pay_id').val('');
        $('input').val('');
        $('textarea').val('');
    })

    $('#modal-pay').on('hidden.bs.modal', function() {
        $('#table_id').val('');
    })

    $(document).on('submit', '#tax-full', function(e) {
        e.preventDefault();
        var pay_id = $('#pay_id').val();
        var name = $('#name').val();
        var tel = $('#tel').val();
        var tax_id = $('#tax_id').val();
        var address = $('#address').val();
        var urlReceipt = '<?= url('admin/order/printReceiptfull') ?>/' + pay_id + '?name=' + name + '&tel=' + tel + '&tax_id=' + tax_id + '&address=' + address;
        window.location.href = urlReceipt;
    });

    $(document).on('click', '.cancelOrderSwal', function(e) {
        var id = $(this).data('id');
        $('#modal-detail').modal('hide');
        Swal.fire({
            title: "ต้องการยกเลิกออเดอร์นี้ใช่หรือไม่",
            showCancelButton: true,
            confirmButtonText: "ยืนยัน",
            denyButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.ajax({
                    type: "post",
                    url: "{{ route('cancelOrder') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.status == true) {
                            $('#myTable').DataTable().ajax.reload(null, false);
                            Swal.fire(response.message, "", "success");
                        } else {
                            Swal.fire(response.message, "", "error");
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.cancelMenuSwal', function(e) {
        var id = $(this).data('id');
        $('#modal-detail').modal('hide');
        Swal.fire({
            title: "ต้องการยกเลิกเมนูนี้ใช่หรือไม่",
            showCancelButton: true,
            confirmButtonText: "ยืนยัน",
            denyButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.ajax({
                    type: "post",
                    url: "{{ route('cancelMenu') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.status == true) {
                            $('#myTable').DataTable().ajax.reload(null, false);
                            Swal.fire(response.message, "", "success");
                        } else {
                            Swal.fire(response.message, "", "error");
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.update-status', function(e) {
        var id = $(this).data('id');
        $('#modal-detail').modal('hide');
        Swal.fire({
            title: "<h5>ท่านต้องการอัพเดทสถานะรายการนี้ใช่หรือไม่</h5>",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "ยืนยัน",
            denyButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.ajax({
                    type: "post",
                    url: "{{ route('updatestatus') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.status == true) {
                            $('#myTable').DataTable().ajax.reload(null, false);
                            Swal.fire(response.message, "", "success");
                        } else {
                            Swal.fire(response.message, "", "error");
                        }
                    }
                });
            }
        });
    });
    $(document).on('click', '.updatestatusOrder', function(e) {
        var id = $(this).data('id');
        $('#modal-detail').modal('hide');
        Swal.fire({
            title: "<h5>ท่านต้องการอัพเดทสถานะรายการนี้ใช่หรือไม่</h5>",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "ยืนยัน",
            denyButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                $.ajax({
                    type: "post",
                    url: "{{ route('updatestatusOrder') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.status == true) {
                            $('#myTable').DataTable().ajax.reload(null, false);
                            Swal.fire(response.message, "", "success");
                        } else {
                            Swal.fire(response.message, "", "error");
                        }
                    }
                });
            }
        });
    });
    $(document).on('click', '.preview-short', function(e) {
        var id = $(this).data('id');
        $('#preview-frame').attr('src', '<?= url('admin/order/printReceipt') ?>/' + id + '?preview=1');
        $('#confirm-print').data('url', '<?= url('admin/order/printReceipt') ?>/' + id);
        $('#print_web').data('url', '<?= url('admin/order/printWeb') ?>/' + id);
        $('#modal-preview').modal('show');
    });

    $(document).on('click', '#preview-tax-full', function(e) {
        e.preventDefault();
        var pay_id = $('#pay_id').val();
        var name = $('#name').val();
        var tel = $('#tel').val();
        var tax_id = $('#tax_id').val();
        var address = $('#address').val();
        var urlPreview = '<?= url('admin/order/printReceiptfull') ?>/' + pay_id + '?name=' + name + '&tel=' + tel + '&tax_id=' + tax_id + '&address=' + address + '&preview=1';
        $('#modal-tax-full').modal('hide');
        $('#preview-frame').attr('src', urlPreview);
        $('#confirm-print').data('url', '<?= url('admin/order/printReceiptfull') ?>/' + pay_id + '?name=' + name + '&tel=' + tel + '&tax_id=' + tax_id + '&address=' + address);
        $('#print_web').data('url', '<?= url('admin/order/printWeb') ?>/' + pay_id);
        $('#modal-preview').modal('show');
    });

    $('#confirm-print').click(function() {
        var iframe = document.getElementById('preview-frame');
        if (iframe && iframe.contentWindow && typeof iframe.contentWindow.sendPrintCommand === 'function') {
            iframe.contentWindow.sendPrintCommand();
        } else {
            // fallback: reload iframe to url (optional)
            var url = $(this).data('url');
            if (url) iframe.src = url;
        }
    });

    $('#print_web').click(function() {
        var url = $(this).data('url');
        $('#preview-frame').attr('src', url);
    });

    // ปุ่มปริ้นแบบธรรมดา (browser print)
    $('#print-browser').click(function() {
        var iframe = document.getElementById('preview-frame');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }
    });

    $('#modal-preview').on('hidden.bs.modal', function() {
        $('#preview-frame').attr('src', '');
        $('#confirm-print').data('url', '');
        $('#print_web').data('url', '');
    });
</script>
@endsection
