<div class="container-fluid mt-4">
    <style>
        /* Style the Image Used to Trigger the Modal */
        .imger {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .imger:hover {
            opacity: 0.7;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            /* width: 50%; */
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 5;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9);
            /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content,
        #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
            }
        }

        /* The Close Button */
        .closeModal {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .closeModal:hover,
        .closeModal:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }

        .accordionContent {
            border: 2px solid var(--navbar-color);
            padding-left: 2px;
            padding-top: 1px;
            padding-right: 1px;
            padding-bottom: 1px;
            background-color: white;
            z-index: 1;
        }

        .accordionContent p {
            margin-left: 0.4rem;
            margin-right: 0.4rem;
            margin-top: 0.4rem;
            margin-bottom: 0.8rem;
        }

        .accordionContent h4 {
            margin-left: 0.4rem;
            margin-right: 0.4rem;
            margin-top: 0.4rem;
        }

        .accordionContent.last {
            position: relative;
            top: -6px;
        }

        .accordionContent .accordionButton {
            margin-left: 5px;
        }

        .accordionContent .accordionContent {
            margin-left: 5px;
        }

        .accordionButton {
            overflow-wrap: anywhere;
            background-color: var(--navbar-color);
            color: #fff;
            cursor: pointer;
            min-height: 60px;
            margin-top: 1px;
            padding: 10px;
            z-index: 2;
        }

        .accordion p {
            color: #16252a !important;
        }

        .accordion .first {
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .accordion .last {
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .accordion .caret {
            margin-left: auto !important;
            font-size: 38px;
        }

        b,
        strong {
            font-weight: bold;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div id="main-accordion" class="accordion">
                <div class="accordionButton first d-flex align-items-center">
                    <h3>
                        Panduan Memulai
                    </h3>
                    <i class="fas fa-caret-down caret"></i>
                </div>
                <div class="accordionContent">
                    <div id="sub-accordion1">
                        <div class="accordionButton first d-flex">
                            <h3>Wiring Nexus Controller</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>
                        <div class="accordionContent">
                            <p>
                                Berikut adalah koneksi atau pinout dari Otoma Nexus
                            </p>
                            <br>
                            <img width="80%" src="img/docs/wiring/nexus-pinout.jpg" alt="Pinout Otoma Nexus" class="imger center">
                            <br>
                            <p>Lalu berikut adalah contoh wiring dari Otoma Nexus yang digunakan dalam aplikasi smart incubator atau smart cultivation</p>
                            <br>
                            <img width="80%" src="img/docs/wiring/nexus-example.jpg" alt="Contoh Wiring Otoma Nexus" class="imger center">
                            <br>
                            <p>Gambar diatas adalah contoh wiring untuk kontroller Otoma Nexus, digunakan kipas axial AC sebagai Pendingin, lampu bohlam sebagai pemanas, motor AC sebagai output 1, dan humidifier sebagai output 2.</p>
                        </div>

                        <div class="accordionButton last d-flex">
                            <h3>Sinkronisasi Akun dan Kontroller</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>
                        <div class="accordionContent last">
                            <p>
                                Pada saat kontroller anda baru pertama kali dinyalakan, kontroller anda perlu diatur agar dapat terhubung dengan internet dan akun anda. Caranya adalah seperti berikut :
                                <br>
                                1. Nyalakan kontroller anda.
                                <br>
                                2. Hubungkan smartphone atau laptop anda ke WiFi yang dipancarkan oleh kontroller. Anda dapat mengetahui nama serta password dari WiFi yang dipancarkan kontroller dengan menavigasi dari Menu Utama lalu ke Info, seperti gambar dibawah ini
                            </p>
                            <img width="30%" style="margin-left:0.4rem;" src="img/docs/getstarted/3.jpg" alt="Layar LCD" class="imger">
                            <img width="30%" src="img/docs/getstarted/4.jpg" alt="Layar LCD" class="imger">
                            <img width="30%" src="img/docs/getstarted/5.jpg" alt="Layar LCD" class="imger">
                            <br>
                            <p>
                                3. Setelah anda sudah terhubung dengan WiFi yang dipancarkan oleh kontroller, buka browser anda lalu masuk ke web address 192.168.4.1 seperti gambar dibawah ini.
                            </p>
                            <img width="50%" src="img/docs/getstarted/14.png" alt="Tampilan Website" class="imger center">
                            <br>
                            <p>4. Apabila tulisan menunjukkan “Belum termuat” coba muat ulang, lalu apabila masih seperti itu maka posisikan smartphone atau laptop anda dekat dengan kontroller agar sinyalnya kuat. Apabila anda sudah berhasil masuk, maka laman akan termuat seperti gambar berikut :</p>
                            <img width="50%" src="img/docs/getstarted/15.png" alt="Tampilan Website" class="imger center">
                            <br>
                            <p>
                                5. Masukkan SSID/Nama WiFi serta password WiFi yang akan anda gunakan untuk kontroller menyambung ke server otoma, lalu masukkan username serta password akun anda yang akan dihubungkan ke kontroller. Apabila semuanya sudah terisi klik tombol “Submit”
                                <br>
                                6. Kontroller akan berhenti memancarkan WiFi dan akan mencoba menyambung pada WiFi yang anda atur diatas. Selama menyambung anda dapat melihat statusnya dari LCD kontroller. Contoh gambar layar LCD seperti berikut :
                            </p>

                            <img width="48%" style="margin-left:0.4rem;" src="img/docs/getstarted/9.jpg" alt="Layar LCD" class="imger">
                            <img width="48%" src="img/docs/getstarted/8.jpg" alt="Layar LCD" class="imger">
                            <br>
                            <p>7. Setelah menunggu beberapa saat, kontroller akan menampilkan layar berikut jika berhasil tersambung ke server otoma.</p>
                            <img width="80%" src="img/docs/getstarted/1.jpg" alt="Layar LCD" class="imger center">
                            <br>
                            <p>Apabila layar LCD menunjukkan seperti diatas, maka langkah selanjutnya adalah merestart kontroller anda dan kontroller anda sudah sinkron dengan akun anda secara online. Adapun cara merestart kontroller anda navigasi dari Menu Utama lalu doubleclick Restart, atau anda juga dapat merestart dari 192.168.4.1, atau anda juga dapat mematikan dan menghidupkan kontroller untuk merestartnya.
                                <br>
                                <br>
                                Apabila proses sinkronisasi gagal maka layar LCD akan menampilkan layar seperti berikut :
                            </p>
                            <img width="80%" src="img/docs/getstarted/7.jpg" alt="Layar LCD" class="imger center">
                            <br>
                            <p>Sesuai dengan pesan LCD, apabila gagal maka tunggu kontroller hingga memancarkan WiFi kembali dan sambungkan kembali smartphone atau laptop anda ke WiFi yang dipancarkan oleh kontroller lalu buka kembali 192.168.4.1 pada browser anda untuk melihat info detail dari kegagalan yang dialami oleh kontroller.</p>
                        </div>
                    </div>
                </div>

                <div class="accordionButton d-flex align-items-center">
                    <h3>
                        Thermocontrol
                    </h3>
                    <i class="fas fa-caret-down caret"></i>
                </div>
                <div class="accordionContent">
                    <p>
                        Thermocontrol adalah fitur yang dimiliki oleh Otoma Nexus yang dapat anda gunakan untuk menyetabilkan suhu suatu objek atau ruang.
                        <br><br>
                        Thermocontrol juga dapat anda aktifkan dan nonaktifkan secara manual maupun secara otomatis (dengan program otomasi), apabila anda nonaktifkan maka pemanas dan pendingin akan mati, sedangkan apabila aktif maka pemanas dan pendingin dapat menyala.
                        <br><br>
                        Thermocontrol mempunyai 2 Operasi, yaitu Manual dan Auto. Dan mempunyai 3 Mode yaitu Heating, Cooling dan Dual.
                        <br><br>
                        Tiap komponen thermocontrol (pemanas dan pendingin) dapat anda atur parameternya serta juga dapat anda kontrol sesuai kebutuhan.
                    </p>

                    <div id="sub-accordion2">
                        <div class="accordionButton first d-flex">
                            <h3>Perbedaan PID dan Hysteresis</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>

                        <div class="accordionContent">
                            <p>
                                PID dan Hysteresis adalah algoritma yang dapat digunakan dalam Otoma Nexus untuk menyetabilkan suhu.
                                <br><br>
                                Dalam kasus pemanas, algoritma hysteresis akan menyalakan pemanas apabila suhu sudah turun dari set poin dikurangi batas bawah pengaturan, lalu akan mematikan pemanas apabila suhu sudah naik dari set poin ditambah batas atas pengaturan. Dengan seperti ini suhu aktual berbentuk seperti segitiga karena karakteristik on/off dari algoritma ini.
                                <br><br>
                                Sedangkan dalam algoritma PID, kontroller melakukan perhitungan yang kompleks untuk menentukan kondisi pemanas. Sehingga dapat didapatkan suhu yang dekat dan akurat dengan setpoin yang telah diatur.
                                <br>
                            </p>

                            <img width="80%" src="img/docs/therco/figure1.jpg" alt="Grafik PID vs Hysteresis" class="imger center">
                            <p>Mengatur Hysteresis dapat dilakukan dengan sangat mudah dan cepat, sedangkan untuk tuning atau mengatur PID diperlukan waktu dan analisis.</p>
                            <br>
                        </div>

                        <div class="accordionButton d-flex">
                            <h3>Mengatur Parameter PID</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>

                        <div class="accordionContent">
                            <p>
                                Dalam mengatur parameter PID, anda dapat melakukannya dalam dua cara yaitu metode tebak-amati atau metode analisa-hitung
                                <br><br>
                                Apapun metodenya kami sarankan untuk menggunakan Durasi Siklus lebih dari 30 detik untuk Otoma Nexus (Relay Output) agar keawetan kontroller terjaga, jika anda menggunakan Otoma Nexus (SSR Output) maka anda dapat menggunakan Durasi Siklus kurang dari 30 detik dengan aman.
                                <br><br>
                                Dalam metode tebak-amati anda merubah parameter Kp, Ki dan Kd dan mengamati output atau perubahan suhu lalu mengubah parameter-parameter dengan menebak hingga mencapai suhu yang mendekati setpoin dengan akurat. Kami merekomendasikan untuk memulai Kp, Ki dan Kd dari nol semua, lalu menaikkan masing-masing secara bertahap.
                                <br><br>
                                Sedangkan dalam metode analisa-hitung anda dapat menggunakan metode Ziegler-Nichols, yaitu salah satu prosedur yang banyak digunakan dalam industri. Caranya yaitu memulai Kp, Ki dan Kd dari nol lalu menaikkan Kp sehingga terjadi osilasi yang berkelanjutan (stabil amplitudonya dan stabil periode waktunya). Setelah didapatkan hasil tersebut, catat Kp menjadi Ku dan hitung periode waktu osilasi dan catat sebagai Tu. Lalu gunakan kedua variabel (Ku dan Pu) tersebut untuk mengatur Kp Ki Kd yang pas sesuai tabel berikut :
                                <br>
                            </p>
                            <img width="80%" src="img/docs/therco/tuning.jpg" alt="Tabel Tuning Paramter PID" class="imger center">
                            <p>Anda juga dapat menggunakan prosedur alternatif dari Ziegler-Nichols seperti Relay (Åström–Hägglund) atau Cohen–Coon parameters.</p>
                        </div>

                        <div class="accordionButton last d-flex">
                            <h3>Mengatur Parameter Histeresis</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>

                        <div class="accordionContent last">
                            <p>
                                Dalam mengatur parameter Hysteresis anda hanya perlu mengatur dua parameter saja yaitu Batas Atas dan Batas Bawah.
                                <br><br>
                                Pemanas akan aktif jika suhu sekarang turun dari Setpoin - Batas Bawah, dan akan nonaktif jika suhu naik dari Setpoin + Batas Atas.
                                <br><br>
                                Pendingin akan aktif jika suhu sekarang naik dari Setpoin + Batas Atas, dan akan nonaktif jika suhu turun dari Setpoin – Batas Bawah.
                                <br><br>
                                Semakin kecil atau sempit Batas Atas/Bawah maka semakin akurat suhu aktual dengan suhu setpoin, akan tetapi keawetan Otoma Nexus dengan Relay Output akan turun dengan lebih cepat. Namun Otoma Nexus dengan SSR Output tidak akan terpengaruh.
                            </p>
                        </div>
                    </div>
                </div>


                <div class="accordionButton last d-flex align-items-center">
                    <h3>
                        Program Otomasi
                    </h3>
                    <i class="fas fa-caret-down caret"></i>
                </div>
                <div class="accordionContent last">
                    <p>
                        Program Otomasi adalah fitur yang dapat digunakan untuk mengotomasi output dari kontroller berdasarkan suatu pemicu, contohnya anda dapat membuat output menyala dari jam 5 pagi hingga jam 6 pagi atau output menyala hanya jika suhu naik dari 28 derajat celcius.
                        <br>
                        Adapun macam pemicu yang anda dapat gunakan adalah :
                        <br>
                    </p>
                    <h4><b>1. Nilai Suhu</b></h4>
                    <p>
                        Adalah pemicu berdasarkan nilai suhu, apabila kondisi dari pemicu terpenuhi maka controller akan melaksanakan aksi dan apabila tidak terpenuhi tidak akan melakukan apapun.
                        Anda dapat menentukan operator yang digunakan seperti “>” (lebih dari) “<” (kurang dari), serta dapat mengatur nilai suhu yang akan digunakan dari 0 derajat celcius hingga 100.9 derajat celcius. <br>
                            Contohnya :<br>
                    </p>
                    <img width="80%" src="img/docs/prog/suhuex1.jpg" alt="Contoh Program Suhu" class="imger center">
                    <br>
                    <p>
                        Yang berarti kontroller akan menyalakan pendingin apabila suhu sudah lebih dari 38.2 celcius.
                        <br>
                    </p>
                    <h4><b>2. Nilai Humiditas</b></h4>
                    <p>
                        Adalah pemicu berdasarkan nilai humiditas, apabila kondisi dari pemicu terpenuhi maka controller akan melaksanakan aksi dan apabila tidak terpenuhi tidak akan melakukan apapun.
                        Anda dapat menentukan operator yang digunakan seperti “>” (lebih dari) “<” (kurang dari), serta dapat mengatur nilai humiditas yang akan digunakan dari 0 persen RH hingga 100 persen RH. <br>
                            Contohnya :
                            <br>
                    </p>
                    <img width="80%" src="img/docs/prog/humidex1.jpg" alt="Contoh Program Humiditas" class="imger center">
                    <br>
                    <p>
                        Yang berarti kontroller akan menyalakan output 1 apabila nilai humiditas telah turun kurang dari 50% RH.
                        <br>
                    </p>
                    <h4><b>3. Jadwal Harian</b></h4>
                    <p>
                        Adalah pemicu berdasarkan jadwal harian, apabila jam sekarang memasuki waktu yang telah diatur maka kontroller akan melaksanakan aksi, dan apabila sudah tidak memasuki waktu yang telah diatur kontroller akan melakukan kebalikan dari aksi (apabila aksi adalah menyalakan, maka kontroller akan mematikan).
                        <br>
                        Contohnya :
                        <br>
                    </p>
                    <img width="80%" src="img/docs/prog/jdwlex1.jpg" alt="Contoh Program Jadwal Harian" class="imger center">
                    <br>
                    <p>
                        Yang berarti apabila jam waktu sekarang diantara jam 10:00:00 hingga jam 15:30:00 maka kontroller akan menyalakan output 1, dan akan mematikan output 1 apabila jam sekarang sudah lewat dari jam yang diatur.
                        <br>
                    </p>
                    <h4><b>4. Tanggal Waktu</b></h4>
                    <p>
                        Adalah pemicu berdasarkan tanggal dan waktu, jadi apabila tanggal waktu sekarang memasuki tanggal waktu yang telah diatur maka kontroller akan melaksanakan aksi, dan akan membalik aksi (apabila aksi adalah menyalakan maka kontroller akan mematikan) saat tanggal waktu sekarang sudah tidak masuk tanggal waktu yang telah diatur.
                        <br>
                        Contohnya :
                        <br>
                    </p>
                    <img width="80%" src="img/docs/prog/tglwex1.jpg" alt="Contoh Program Tanggal Waktu" class="imger center">
                    <br>
                    <p>
                        Yang berarti apabila tanggal waktu sekarang sudah memasuki tanggal 31 Oktober 2020 Jam 12:00 maka kontroller akan menyalakan thermocontrol, lalu apabila tanggal sekarang sudah lewat dari tanggal 6 November 2020 Jam 12:00 maka kontroller akan mematikan thermocontrol.
                        <br>
                    </p>
                    <h4><b>5. Keadaan</b></h4>
                    <p>
                        Adalah pemicu berdasarkan kondisi dari output yang dimiliki oleh kontroller, apabila kondisi output sesuai dengan yang diatur maka kontroller akan melaksanakan aksi.
                        <br>
                        Contohnya :
                    </p>
                    <img width="80%" src="img/docs/prog/kdnex1.jpg" alt="Contoh Program Keadaan" class="imger center">
                    <br>
                    <p>Yang berarti apabila keadaan pemanas menyala maka kontroller akan mematikan pendingin.
                        <br>
                        <br>
                        List macam pemicu diatas mungkin akan bertambah seiring waktu karena update yang kami tambahkan.
                        <br>
                        <br>
                        Anda dapat membuat maksimum 30 program per kontroller.
                        <br>
                        <br>
                        Setelah anda membuat atau mengubah program pastikan untuk menombol tombol “Update” agar perubahan tersimpan, lalu apabila anda ingin menghapus program cukup tekan tombol “Hapus”
                        Program dengan nomor yang besar (atau berada dibawah) mempunyai prioritas yang lebih tinggi daripada yang nomor kecil (diposisi atas).
                        <br>
                        Contohnya :
                        <br>
                    </p>
                    <img width="80%" src="img/docs/prog/progex.jpg" alt="Urutan Prioritas Program" class="imger center">
                    <br>
                    <p>
                        Apabila nilai suhu lebih dari 36 derajat celcius (Program 2) maka <b>kontroller akan mematikan output 1 walaupun</b> jam sekarang diantara jam 10:00:00 hingga jam 14:00:00 (Program 1). Jadi apabila kondisi pemicu Program 2 terpenuhi yang diprioritaskan adalah program yang nomor besar/bawah yaitu Program 2 daripada Program yang mempunyai nomor kecil/atas (Program 1).
                        <br>
                        <br>
                        Lalu yang perlu diperhatikan adalah bagaimana kontroller melaksanakan aksi, pada pemicu <strong>Jadwal Harian</strong> dan <b>Tanggal Waktu</b>, kontroller akan membalik aksi apabila kondisi tidak terpenuhi (apabila aksi adalah menyalakan maka kontroller akan mematikan). Sedangkan pada pemicu Nilai Suhu, <b>Nilai Humiditas</b> dan <b>Keadaan</b>, kontroller hanya akan melaksanakan aksi apabila kondisi pemicu terpenuhi, dan apabila tidak terpenuhi kontroller tidak akan melakukan apapun. Jadi apabila dibuat program untuk menyalakan Pemanas apabila suhu kurang dari 30, kontroller akan menyalakan pemanas saat suhu kurang dari 30 dan akan menyala terus menerus, apabila anda ingin mematikan pemanas tersebut maka anda perlu membuat program baru untuk mematikannya, contohnya apabila suhu sudah lebih dari 33 derajat maka matikan pemanas.
                        <br>
                        <br>
                        Apabila anda masih kebingungan, memiliki pertanyaan atau ingin request fitur anda dapat menghubungi kami di laman “Kontak Kami”, selain itu kami juga dapat membantu anda untuk membuat program yang anda inginkan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div id="imgModal" class="modal">

    <!-- The Close Button -->
    <span class="closeModal" style="position: absolute;top: 55px;right: 10px;">&times;</span>

    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">

    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>
<script>
    // Get the modal
    var modal = document.getElementById("imgModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementsByClassName("imger");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    var i;
    for (i = 0; i < img.length; i++) {
        img[i].onclick = function() {
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }
    }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("closeModal")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>