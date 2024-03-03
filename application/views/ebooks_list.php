<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>KBE Challenge</title>
	<!-- Include PDF.js library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <!-- Include PDF.js CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf_viewer.css">
	<!-- Include Bootstrap CSS  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<style>
    /* CSS untuk PDF container */
    #pdf-container {
        overflow: auto; /* Membuat konten dapat di-scroll */
        height: 500px; /* Tentukan tinggi yang sesuai untuk container */
    }

    /* CSS untuk modal peringatan */
    #modalWarn {
        max-width: 50%; /* Atur lebar maksimum modal */
        margin: 1.2rem auto; /* Jarak dari atas dan bawah */
    }

    /* CSS untuk membuat modal scrollable */
    .modal-dialog {
        max-width: 75%; /* Atur lebar maksimum modal */
        margin: 1.2rem auto; /* Jarak dari atas dan bawah */
    }

    .modal-body {
        max-height: calc(100vh - 100px); /* Tentukan tinggi maksimum untuk konten modal */
        overflow-y: auto; /* Membuat konten modal dapat di-scroll */
    }
    /* CSS untuk membuat konten berada di tengah */
    .center {
      margin: auto;
      width: 60%;
      padding: 10px;
    }
    /* CSS untuk membuat margin bottom */
    .mb-small {
        margin-bottom: 20px;
    }    
    </style>
</head>
<body>
<div>
    <div class="container">
    <div class="center">
        <h1 class="text-center mb-small">Daftar Ebook</h1>        
        <div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col" class="text-center">Nama Ebook</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($ebooks as $ebook): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $ebook->ebook_name; ?></td>
                    <td class="text-center">
                        <button class="btn btn-primary" onclick="openAlert('<?php echo base_url('files_controller/file/'.strtr(base64_encode($ebook->pathfile), '+/=', '-_.').'?token='.$token);?>')">View Ebook</button>
                        <a href="<?php echo base_url('ebooks_controller/delete_ebook/'.$ebook->id); ?>"><button class="btn btn-danger">Delete</button></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div>
            <p><span class="font-weight-bold">Github repo : </span><a href="https://github.com/arifwidiasan/kbe-challenge"> kbe-challenge </a></p>
            <p><span class="font-weight-bold">Deploy : </span><a href="https://kbe-arif.000webhostapp.com/"> kbe-arif.000webhostapp.com </a></p>
        </div>
    </div>
    </div>

	<!-- Modal Alert-->
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div id="modalWarn" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Peringatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
					<div>Tidak diperkenankan membuka tab lain saat membuka PDF. Jika terjadi, maka tes akan dianggap selesai (contoh) </div>
                </div>
                <div class="modal-footer">
                    <!-- Navigation -->
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                    <button type="button" class="btn btn-primary" onclick="openPdfModal()">Ok</button>					
                </div>
            </div>
        </div>
    </div>

  <!-- Modal PDF-->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Ebook</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tempat konten PDF -->
                    <div id="pdf-container"></div>
                </div>
                <div class="modal-footer">
                    <!-- Navigation -->
                    <button type="button" class="btn btn-primary" onclick="prevPage()">Previous</button>
                    <span id="page-num"></span> <!-- Indikator nomor halaman -->
                    <button type="button" class="btn btn-primary" onclick="nextPage()">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script untuk menampilkan PDF -->
<script>
    var currentPage = 1;
    var totalPages = 0;
    var pdfFile = null;
    var pdfUrl = null;
    // Fungsi untuk menampilkan modal peringatan sebelum membuka pdf
    function openAlert(url){
        $('#alertModal').modal('show');
        pdfUrl = url;
    }

    function openPdfModal() {
        // Reset current page to 1 when opening the modal
        $('#alertModal').modal('hide');
        currentPage = 1;
        $('#pdfModal').modal('show');
        loadPdf(pdfUrl);
    }

    function loadPdf(pdfUrl) {
        // menampilkan pdf dengan pdf.js

        if (pdfFile) {
            displayPage(currentPage, pdfFile);
            return;
        }
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            totalPages = pdf.numPages;
            pdfFile = pdf;
            displayPage(currentPage, pdf);
        });
    }
    // Fungsi untuk menampilkan halaman PDF
    function displayPage(pageNumber, pdf) {
        pdf.getPage(pageNumber).then(function(page) {
            var scale = 1.5;
            var viewport = page.getViewport({ scale: scale });
            var canvas = document.createElement("canvas");
            var context = canvas.getContext("2d");
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            var renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext).promise.then(function() {
                document.getElementById("pdf-container").innerHTML = "";
                document.getElementById("pdf-container").appendChild(canvas);
                updatePageNumber(pageNumber);
            });
        });
    }
    // Fungsi untuk menampilkan halaman sebelumnya
    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            loadPdf();
        }
    }
    // Fungsi untuk menampilkan halaman berikutnya
    function nextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            loadPdf();
        }
    }
    // indikator update halaman
    function updatePageNumber(pageNumber) {
        document.getElementById("page-num").innerText = "Page " + pageNumber + " of " + totalPages;
    }
</script>

<!-- Script untuk menangani peristiwa right-click dan print -->
<script>
	var isModalOpen = false;
    // Fungsi untuk menangani peristiwa klik kanan
    function disableRightClick(event) {
        event.preventDefault();
    }

    // Fungsi untuk menangani peristiwa kunci keyboard
    function disablePrint(event) {
        // Menonaktifkan tombol Ctrl+P
        if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
            event.preventDefault();
        }
    }

    // Ketika modal ditampilkan
    $('#pdfModal').on('shown.bs.modal', function () {
        // Menangkap peristiwa klik kanan pada dokumen
        document.addEventListener('contextmenu', disableRightClick);
        // Menangkap peristiwa ketika tombol keyboard ditekan pada dokumen
        document.addEventListener('keydown', disablePrint);
		isModalOpen = true;
    });

    // Ketika modal disembunyikan
    $('#pdfModal').on('hidden.bs.modal', function () {
        // Menghapus event listener untuk klik kanan
        document.removeEventListener('contextmenu', disableRightClick);
        // Menghapus event listener untuk tombol keyboard
        document.removeEventListener('keydown', disablePrint);
		//refresh page saat modal ditutup
		location.reload();
		isModalOpen = false;
    });
</script>

<!-- Script untuk menangani peristiwa user pindah tab -->
<script>
	// Ketika jendela kehilangan fokus (user pindah ke tab lain)
	window.addEventListener('blur', function() {
		if (isModalOpen) {
			alert('Karena tidak bisa mencegah user untuk pindah tab, maka saat user kembali ke tab akan ada peringatan dan diberi dihandle seperti "tes dianggap selesai" atau yang lain.');	
			//location.reload();
		}
	});

</script>

</html>
