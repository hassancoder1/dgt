<?php include("header.php"); ?>
<?php if (isset($_SESSION['response'])) {
    echo $_SESSION['response'];
    unset($_SESSION['response']);
} ?>
<div class="row">
    <div class="col-xxl-9">
        <div class="row">
            <div class="col-xl-4 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                    <div class="avatar-title rounded bg-primary bg-gradient">
                                        <i data-eva="pie-chart-2" class="fill-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Customers</p>
                                <h4 class="mb-0">45,254</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-end ms-2">
                                <div class="badge rounded-pill font-size-13 badge-soft-success">+ 2.65%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                    <div class="avatar-title rounded bg-primary bg-gradient">
                                        <i data-eva="shopping-bag" class="fill-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Orders</p>
                                <h4 class="mb-0">5,643</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-end ms-2">
                                <div class="badge rounded-pill font-size-13 badge-soft-danger">- 0.82%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                    <div class="avatar-title rounded bg-primary bg-gradient">
                                        <i data-eva="people" class="fill-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Accounts</p>
                                <h4 class="mb-0"><?php echo getNumRows('khaata') ?></h4>

                            </div>
                            <div class="flex-shrink-0 align-self-end ms-2">
                                <div class="badge rounded-pill font-size-13 badge-soft-danger">- 1.04%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-3">Transaction</h5>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <span class="fw-semibold">Report By:</span> <span
                                    class="text-muted">Monthly<i
                                        class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Yearly</a>
                                <a class="dropdown-item" href="#">Monthly</a>
                                <a class="dropdown-item" href="#">Weekly</a>
                                <a class="dropdown-item" href="#">Today</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="align-middle">Order ID</th>
                            <th class="align-middle">Billing Name</th>
                            <th class="align-middle">Date</th>
                            <th class="align-middle">Total</th>
                            <th class="align-middle">Pay Status</th>
                            <th class="align-middle">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><a href="javascript: void(0);" class="text-body fw-semibold">#BR2149</a>
                            </td>
                            <td>James</td>
                            <td>
                                07 Oct, 2021
                            </td>
                            <td>
                                $26.15
                            </td>
                            <td class="text-center">
                                                <span
                                                    class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary bg-gradient btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="View">
                                        <i data-eva="eye" data-eva-height="14" data-eva-width="14"
                                           class="fill-white align-text-top"></i>
                                    </button>
                                    <button type="button" class="btn btn-success bg-gradient btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit">
                                        <i data-eva="edit" data-eva-height="14" data-eva-width="14"
                                           class="fill-white align-text-top"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger bg-gradient btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete">
                                        <i data-eva="trash-2" data-eva-height="14" data-eva-width="14"
                                           class="fill-white align-text-top"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="javascript: void(0);" class="text-body fw-semibold">#BR2148</a>
                            </td>
                            <td>Jill</td>
                            <td>
                                06 Oct, 2021
                            </td>
                            <td>
                                $21.25
                            </td>
                            <td class="text-center">
                                                <span
                                                    class="badge badge-pill badge-soft-warning font-size-11">Refund</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary bg-gradient btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="View">
                                        <i data-eva="eye" data-eva-height="14" data-eva-width="14"
                                           class="fill-white align-text-top"></i>
                                    </button>
                                    <button type="button" class="btn btn-success bg-gradient btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit">
                                        <i data-eva="edit" data-eva-height="14" data-eva-width="14"
                                           class="fill-white align-text-top"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger bg-gradient btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete">
                                        <i data-eva="trash-2" data-eva-height="14" data-eva-width="14"
                                           class="fill-white align-text-top"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- end table -->
                </div>
                <!-- end table responsive -->
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<?php include("footer.php"); ?>
