            <section class="py-5">
                <div class="container px-5 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradientX d-inline">Cat Facts</span></h1>
                    </div>
                    <div class="row gx-5 justify-content-center">
                        <div class="col-lg-12 col-xl-12 col-xxl-12">
                            <div class="card overflow-hidden shadow rounded-4 border-0 mb-5">
                                <div class="card-body p-5">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody id="fact-table-body"></tbody>
                                        </table>
                                        <a href="/" class="btn btn-outline-dark text mb-3 " >
                                        Back
                                        </a>
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