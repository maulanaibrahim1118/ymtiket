{{-- Antrikan Modal --}}
<div class="modal fade w-100" id="antrikanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent1">
        </div>
    </div>
</div><!-- End Vertically centered Modal-->
<script>
    // Fungsi untuk menampilkan data pada modal
    function tampilkanData1(ticket_id) {
        // Mendapatkan elemen modalContent
        var modalContent1 = document.getElementById("modalContent1");
    
        // Menampilkan data pada modalContent
        if(ticket_id.value == 2){
            modalContent1.innerHTML  =
            '<div class="modal-header">'+
                '<h5 class="modal-title">.:: Queue Ticket</h5>'+
                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
            '</div>'+
            '<form action="/tickets/queue" method="post">'+
            '@method("put")'+
            '@csrf'+
            '<div class="modal-body">'+
                '<div class="col-md-12">'+
                    '<label for="sub_divisi" class="form-label">Sub Division</label>'+
                    '<select class="form-select" name="sub_divisi" id="sub_divisi" required>'+
                        '<option selected disabled>Choose...</option>'+
                        '@foreach($subDivHo as $subDiv)'+
                            '@if(old("sub_divisi") == $subDiv)'+
                            '<option selected value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>'+
                            '@else'+
                            '<option value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>'+
                            '@endif'+
                        '@endforeach'+
                    '</select>'+
                '</div>'+
                '<input type="text" id="ticket_id" name="id" value="'+ticket_id.name+'" hidden>'+
            '</div>'+
            '<div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary"><i class="bi bi-list-check me-2"></i>Queue</button>'+
            '</div>'+
            '</form>';

            $("#sub_divisi").select2({
                dropdownParent: $("#sub_divisi").parent(), // Menentukan parent untuk dropdown
            });
        }else{
            modalContent1.innerHTML  =
            '<div class="modal-header">'+
                '<h5 class="modal-title">.:: Queue Ticket</h5>'+
                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
            '</div>'+
            '<form action="/tickets/queue" method="post">'+
            '@method("put")'+
            '@csrf'+
            '<div class="modal-body">'+
                '<div class="col-md-12">'+
                    '<label for="sub_divisi" class="form-label">Sub Division</label>'+
                    '<select class="form-select" name="sub_divisi" id="sub_divisi" required>'+
                        '<option selected disabled>Choose...</option>'+
                        '@foreach($subDivStore as $subDiv)'+
                            '@if(old("sub_divisi") == $subDiv)'+
                            '<option selected value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>'+
                            '@else'+
                            '<option value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>'+
                            '@endif'+
                        '@endforeach'+
                    '</select>'+
                '</div>'+
                '<input type="text" id="ticket_id" name="id" value="'+ticket_id.name+'" hidden>'+
            '</div>'+
            '<div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary"><i class="bi bi-list-check me-2"></i>Queue</button>'+
            '</div>'+
            '</form>';

            $("#sub_divisi").select2({
                dropdownParent: $("#sub_divisi").parent(), // Menentukan parent untuk dropdown
            });
        }
    }
</script>

{{-- Assign Modal --}}
<div class="modal fade w-100" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent2">
        </div>
    </div>
</div><!-- End Vertically centered Modal-->
<script>
    // Fungsi untuk menampilkan data pada modal
    function tampilkanData2(ticket_id) {
        // Mendapatkan elemen modalContent
        var modalContent2 = document.getElementById("modalContent2");
    
        // Menampilkan data pada modalContent
        if(ticket_id.value == 2){
            modalContent2.innerHTML  =
            '<div class="modal-header">'+
                '<h5 class="modal-title">.:: Assign Ticket</h5>'+
                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
            '</div>'+
            '<form action="/tickets/assign" method="post">'+
            '@method("put")'+
            '@csrf'+
            '<div class="modal-body">'+
                '<div class="col-md-12">'+
                    '<label for="agent_id" class="form-label">Agent Name</label>'+
                    '<select class="form-select select2" name="agent_id" id="agent_id" required>'+
                        '<option selected disabled>Choose...</option>'+
                        '@foreach($hoAgents as $hoAgent)'+
                            '@if(old("agent_id") == $hoAgent->id)'+
                            '<option selected value="{{ $hoAgent->id }}">{{ ucwords($hoAgent->nama_agent) }}</option>'+
                            '@else'+
                            '<option value="{{ $hoAgent->id }}">{{ ucwords($hoAgent->nama_agent) }}</option>'+
                            '@endif'+
                        '@endforeach'+
                    '</select>'+
                '</div>'+
                '<input type="text" id="ticket_id" name="ticket_id" value="'+ticket_id.name+'" hidden>'+
            '</div>'+
            '<div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary"><i class="bx bx-share me-2"></i>Assign</button>'+
            '</div>'+
            '</form>';

            // Inisialisasi ulang Select2 setelah mengganti konten modal
            $("#agent_id").select2({
                dropdownParent: $("#agent_id").parent(), // Menentukan parent untuk dropdown
            });
        }else{
            modalContent2.innerHTML  =
            '<div class="modal-header">'+
                '<h5 class="modal-title">.:: Assign Ticket</h5>'+
                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
            '</div>'+
            '<form action="/tickets/assign" method="post">'+
            '@method("put")'+
            '@csrf'+
            '<div class="modal-body">'+
                '<div class="col-md-12">'+
                    '<label for="agent_id" class="form-label">Agent Name</label>'+
                    '<select class="form-select" name="agent_id" id="agent_id" required>'+
                        '<option selected disabled>Choose...</option>'+
                        '@foreach($storeAgents as $storeAgent)'+
                            '@if(old("agent_id") == $storeAgent->id)'+
                            '<option selected value="{{ $storeAgent->id }}">{{ ucwords($storeAgent->nama_agent) }}</option>'+
                            '@else'+
                            '<option value="{{ $storeAgent->id }}">{{ ucwords($storeAgent->nama_agent) }}</option>'+
                            '@endif'+
                        '@endforeach'+
                    '</select>'+
                '</div>'+
                '<input type="text" id="ticket_id" name="ticket_id" value="'+ticket_id.name+'" hidden>'+
            '</div>'+
            '<div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary"><i class="bx bx-share me-2"></i>Assign</button>'+
            '</div>'+
            '</form>';

            // Inisialisasi ulang Select2 setelah mengganti konten modal
            $("#agent_id").select2({
                dropdownParent: $("#agent_id").parent(), // Menentukan parent untuk dropdown
            });
        }
    }
</script>