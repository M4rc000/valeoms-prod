<div class="modal fade" id="modalglobal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-header">
            <h5 class="modal-title">Edit Material</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="modal-content" id="modal-content">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                onclick="closeModal()">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</div>
<script>
function showModal(title = '', link = '', datas = {}) {
    $.ajax({
        url: link,
        type: 'post',
        dataType: 'json',
        data: datas,
        success: function(res) {
            $('#modalglobal').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.error(thrownError)
        }
    })
}
</script>