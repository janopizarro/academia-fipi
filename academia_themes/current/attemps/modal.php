<style>
.modal {
    z-index: 2000
}

.modal iframe {
    height: 395px !important;
}

.modal .modal-dialog {
    max-width: 680px;
}
</style>

<!-- Modal -->
<div class="modal fade" id="modalVideoAttemp" tabindex="-1" role="dialog"
    aria-labelledby="modalVideoAttempLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVideoAttempLabel" style="font-size: 20px;">Video Unidad
                    <strong id="attempUnit"></strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="attempLoading" style="display:none">Cargando video...</div>
                <div id="attempsRemain"></div>
                <div id="attempVideo"></div>
                <div id="attempError"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    style="padding: 3px 10px; font-size: 17px;">Cerrar</button>
            </div>
        </div>
    </div>
</div>