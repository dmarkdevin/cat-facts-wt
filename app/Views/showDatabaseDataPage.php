            <section class="py-5">
                <div class="container px-5 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradientX d-inline">Database Data</span></h1>
                    </div>
                    <div class="row gx-5 justify-content-center">
                        <div class="col-lg-12 col-xl-12 col-xxl-12">
                            <?php
                            if (session()->getFlashdata('status_success')) {
                                echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <h4 class="alert-heading">Success</h4>
                                        <p>'.session()->getFlashdata('status_success').'</p>
                                    </div>';
                            }
                            ?>
                            <a href="<?= base_url('/sync'); ?>"  class="btn btn-dark text mb-3 " >
                                Sync API Data
                            </a>

                            <div class="card overflow-hidden shadow rounded-4 border-0 mb-5">
                                <div class="card-body p-5">

                                    <?php if ($count>0) { ?>
                                        <p>The following data is from the Database - Table <b>facts</b>.</p>
                                    <?php } ?>

                                    <table class="table">
                                        <tbody id="fact-table-body"></tbody>
                                    </table>
                                    <?php if ($count>0) { ?>
                                    <div class="text-center mt-5">

                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmClearModal">
                                            Clear Data
                                        </button>

                                        <a href="/" class="btn btn-outline-dark text"> Cancel</a>

                                    </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="modal" id="confirmClearModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to clear the <b>facts</b> table in the database?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="/cleardata" method="post">
                                                <input type="hidden" name="token" value="<?=$token;?>">
                                                <input type="submit" name="confirm" value="Confirm Clear Data" class="btn btn-danger text">  
                                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <script>
                function updateFactTable() {
                    $.ajax({
                        url: '/fetchCatFactsData',
                        type: 'GET',
                        success: function(response) {
                        $('#fact-table-body').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
                updateFactTable();
                setInterval(updateFactTable, 5000);
            </script>