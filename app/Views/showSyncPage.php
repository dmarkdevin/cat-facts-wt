            <section class="py-5">
                <div class="container px-5 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradientX d-inline">Sync Data</span></h1>
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
                            if (session()->getFlashdata('status_error')) {
                                echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <h4 class="alert-heading">Error</h4>
                                        <p>'.session()->getFlashdata('status_error').'</p>
                                    </div>';
                            }
                            ?>
                            <a href="<?= base_url('/databasedata'); ?>" class="btn btn-dark text mb-3 " >
                                Show Database Data (<?=$count;?>)
                            </a>
                            <div class="card overflow-hidden shadow rounded-4 border-0 mb-5">
                                <div class="card-body p-5">
                                    <?php if (!empty($facts)) { ?>
                                        <p>The following data is from the Cat Facts API.</p>
                                    <?php } ?>
                                    <div class="table-responsive">
                                    <table class="table">
                                        <tbody id="fact-table-body"></tbody>
                                    </table>
                                    </div>
                                    <?php if (!empty($facts)) { ?>
                                    <div class="text-center mt-5">
                                        <p>By clicking the confirm button, you confirm the synchronization of data from the Cat Facts API to the database.</p>

                                        <form action="/confirmed" method="post">
                                            <input type="hidden" name="token" value="<?=$token;?>">
                                            <input type="submit" name="confirm" value="Confirm" class="btn btn-dark text">  <a href="/" class="btn btn-outline-dark text"> Cancel</a>
                                        </form>

                                    </div>
                                    <?php } else { ?>
                                        <a href="<?= previous_url() != current_url() ? previous_url():'/'; ?>" class="btn btn-outline-dark text mb-3 " >
                                            Back
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <script>
                function updateFactTable() {
                    $.ajax({
                        url: '/fetchCatFactsAPI',
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
                // setInterval(updateFactTable, 5000);
            </script>