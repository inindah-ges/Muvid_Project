<div class="container-fluid contact py-3 wow bounceInUp">
    <div class="container">
        <div class="p-5 bg-light rounded contact-form">
            <div class="row g-4">
                <div class="col-12 text-center pb-3">
                    <small
                        class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Tetap
                        Terhubung</small>
                    <h1 class="display-5 mb-0">Hubungi Kami untuk Pertanyaan Apapun!</h1>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-7">
                    <p>Mohon isi detail dan pesan di bawah ini untuk memberikan feedback kepada kami.</p>
                    <form id="feedbackForm">
                        <!-- Container untuk pesan sukses -->
                        <div id="successMessage" style="display: none;" class="mb-3 alert alert-success">
                            Pesan berhasil dikirim. Terima kasih atas feedback Anda!
                        </div>

                        <!-- Input Form -->
                        <input type="text" name="name" class="w-100 form-control p-3 mb-4 border-primary bg-light"
                            placeholder="Your Name" required>
                        <input type="email" name="email" class="w-100 form-control p-3 mb-4 border-primary bg-light"
                            placeholder="Enter Your Email" required>
                        <textarea name="message" class="w-100 form-control mb-4 p-3 border-primary bg-light" rows="4" cols="10"
                            placeholder="Your Message" required></textarea>

                        <!-- Tombol Submit -->
                        <button class="w-100 btn btn-primary form-control p-3 border-primary bg-primary rounded-pill"
                            type="submit">Submit Now</button>
                    </form>
                </div>

                <script>
                    document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
                        e.preventDefault(); // Mencegah form terkirim secara default

                        const form = e.target;
                        const formData = new FormData(form);

                        try {
                            // Kirim data ke Formspree menggunakan Fetch API
                            const response = await fetch('https://formspree.io/f/mqaknzwv', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok) {
                                // Tampilkan pesan sukses di atas form
                                const successMessage = document.getElementById('successMessage');
                                successMessage.style.display = 'block';
                                successMessage.textContent = 'Pesan berhasil dikirim. Terima kasih atas feedback Anda!';

                                // Reset form agar kosong setelah pengiriman
                                form.reset();
                            } else {
                                alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                        }
                    });
                </script>

                <div class="col-sm-12 col-md-6 col-lg-5 text-break">
                    <div>
                        <div class="d-inline-flex w-100 border border-primary p-4 rounded mb-4">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Address</h4>
                                <p> Jl. Wijaya Kusuma No.6, Makassar</p>
                            </div>
                        </div>
                        <div class="d-inline-flex w-100 border border-primary p-4 rounded mb-4">
                            <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Mail Us</h4>
                                <p class="mb-2">kelompok1@gmail.com</p>
                                <p class="mb-0">info@kanalsocialspace.com</p>
                            </div>
                        </div>
                        <div class="d-inline-flex w-100 border border-primary p-4 rounded">
                            <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Telephone</h4>
                                <p class="mb-2">(+012) 3456 7890 123</p>
                                <p class="mb-0">(+704) 5555 0127 296</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
