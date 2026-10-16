<section class="ftco-section ftco-no-pt bg-light">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-md-12	featured-top">
                <div class="row no-gutters">
                    <div class="col-md-12 align-items-center">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">
                                আপনার ট্রিপের অনুরোধ সফলভাবে জমা হয়েছে।
                            </div>
                        <?php endif; ?>


                        <form action="./sql/trip_requests.php" method="POST"
                            class="request-form ftco-animate bg-primary">
                            <h2>Make your trip</h2>

                            <div class="row">
                                <!-- name   -->
                                <div class="form-group col-md-6">
                                    <label for="dropoff_location" class="label">
                                        Name
                                    </label>

                                    <input type="text" name="name" id="dropoff_location" class="form-control"
                                        placeholder="Your name" required>
                                </div>

                                <!-- phone number  -->
                                <div class="form-group col-md-6">
                                    <label for="phone_number" class="label">
                                        Phone Number
                                    </label>

                                    <input type="text" name="phone_number" id="phone_number" class="form-control"
                                        placeholder="Your Phone Number" required>
                                </div>


                                <!-- Pick-up Location -->
                                <div class="form-group col-md-6">
                                    <label for="pickup_location" class="label">
                                        Pick-up location
                                    </label>

                                    <input type="text" name="pickup_location" id="pickup_location" class="form-control"
                                        placeholder="City, Airport, Station, etc" maxlength="255" required>
                                </div>

                                <!-- Drop-off Location -->
                                <div class="form-group col-md-6">
                                    <label for="dropoff_location" class="label">
                                        Drop-off location
                                    </label>

                                    <input type="text" name="dropoff_location" id="dropoff_location"
                                        class="form-control" placeholder="City, Airport, Station, etc" maxlength="255"
                                        required>
                                </div>
                                <!-- Pick-up Time -->
                                <div class="form-group col-md-6">
                                    <label for="time_pick" class="label">
                                        Pick-up date
                                    </label>

                                    <input type="date" name="pickup_date" class="form-control" required>
                                </div>

                                <!-- Pick-up Time -->
                                <div class="form-group col-md-6">
                                    <label for="time_pick" class="label">
                                        Pick-up time
                                    </label>

                                    <input type="time" name="pickup_time" class="form-control" required>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-secondary py-3 px-4">
                                        Rent A Car Now
                                    </button>
                                </div>

                            </div>
                        </form>

                        <!-- Date Validation -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const pickupDate = document.getElementById('pickup_date');
                                const dropoffDate = document.getElementById('dropoff_date');

                                pickupDate.addEventListener('change', function () {
                                    dropoffDate.min = this.value;

                                    if (dropoffDate.value && dropoffDate.value < this.value) {
                                        dropoffDate.value = this.value;
                                    }
                                });
                            });
                        </script>

                    </div>
                    <!-- <div class="col-md-8 d-flex align-items-center">
                        <div class="services-wrap rounded-right w-100">
                            <h3 class="heading-section mb-4">Better Way to Rent Your Perfect Cars</h3>
                            <div class="row d-flex mb-4">
                                <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                    <div class="services w-100 text-center">
                                        <div class="icon d-flex align-items-center justify-content-center"><span
                                                class="flaticon-route"></span></div>
                                        <div class="text w-100">
                                            <h3 class="heading mb-2">Choose Your Pickup Location</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                    <div class="services w-100 text-center">
                                        <div class="icon d-flex align-items-center justify-content-center"><span
                                                class="flaticon-handshake"></span></div>
                                        <div class="text w-100">
                                            <h3 class="heading mb-2">Select the Best Deal</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                    <div class="services w-100 text-center">
                                        <div class="icon d-flex align-items-center justify-content-center"><span
                                                class="flaticon-rent"></span></div>
                                        <div class="text w-100">
                                            <h3 class="heading mb-2">Reserve Your Rental Car</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p><a href="#" class="btn btn-primary py-3 px-4">Reserve Your Perfect Car</a></p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
</section>