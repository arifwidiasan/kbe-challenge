<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<!-- Include PDF.js library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <!-- Include PDF.js CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf_viewer.css">
	<!-- Include Bootstrap CSS (assuming you're using Bootstrap for modal) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<style>
    /* CSS for PDF container */
    #pdf-container {
        overflow: auto; /* Membuat konten dapat di-scroll */
        height: 500px; /* Tentukan tinggi yang sesuai untuk container */
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
</style>
</head>
<body>

<div>
	<h1>Welcome to CodeIgniter!</h1>

	<div>
		<table>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Path File</th>
				<th>Actions</th>
			</tr>
			<?php foreach ($ebooks as $ebook): ?>
			<tr>
				<td><?php echo $ebook->id; ?></td>
				<td><?php echo $ebook->ebook_name; ?></td>
				<td><?php echo $ebook->pathfile; ?></td>
				<td>
					<button class="btn btn-primary" onclick="openAlert('<?php echo base_url('files_controller/download/'.strtr(base64_encode($ebook->pathfile), '+/=', '-_.').'?token='.$token);?>')">View PDF</button>
					<button href="<?php echo base_url('ebooks_controller/delete_ebook/'.$ebook->id); ?>">Delete</button>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<!-- Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Peringatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- PDF content will be loaded here -->
					<div>Tidak diperkenankan membuka tab lain saat membuka PDF. Jika terjadi, maka PDF akan direset dan user harus membuka PDF dari awal.</div>
                </div>
                <div class="modal-footer">
                    <!-- Navigation buttons -->
                    <button type="button" class="btn btn-primary" onclick="openPdfModal()">Yes</button>
					<button type="button" class="btn btn-primary" onclick="">No</button>
                </div>
            </div>
        </div>
    </div>

  <!-- Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">PDF Viewer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- PDF content will be loaded here -->
                    <div id="pdf-container"></div>
                </div>
                <div class="modal-footer">
                    <!-- Navigation buttons -->
                    <button type="button" class="btn btn-primary" onclick="prevPage()">Previous</button>
                    <span id="page-num"></span> <!-- Page number indicator -->
                    <button type="button" class="btn btn-primary" onclick="nextPage()">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (assuming you're using Bootstrap for modal) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include PDF.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>

    <script>
        var currentPage = 1;
        var totalPages = 0;
		var pdfFile = null;
		var pdfUrl = null;

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
            // Display PDF using PDF.js

			// If PDF is already loaded, just display the page
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

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                loadPdf();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadPdf();
            }
        }

        function updatePageNumber(pageNumber) {
            document.getElementById("page-num").innerText = "Page " + pageNumber + " of " + totalPages;
        }
    </script>

<script>
	var isModalOpen = false;
    // Fungsi untuk menangani peristiwa klik kanan
    function disableRightClick(event) {
        event.preventDefault(); // Mencegah tindakan default (misalnya, menampilkan menu konteks)
    }

    // Fungsi untuk menangani peristiwa kunci keyboard
    function disablePrint(event) {
        // Menonaktifkan tombol Ctrl+P
        if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
            event.preventDefault(); // Mencegah tindakan default (misalnya, mencetak)
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

<script>
	// Ketika jendela kehilangan fokus (user pindah ke tab lain)
	window.addEventListener('blur', function() {
		if (isModalOpen) {
			alert('Karena tidak bisa mencegah user untuk pindah tab, maka saat user kembali ke tab akan ada peringatan dan diberi dihandle seperti "tes tiba - tiba dianggap selesai" atau yang lain.');	
			//location.reload();
		}
	});

</script>
</div>

</body>
</html>
