<script type="text/javascript">
    $(document).ready(function() {
        $('#generate_password').click(function() {
            if ($('#tbl_ligue_password').size()) {
                $.ajax({
                    type: "POST",
                    url:"<?php echo url_for('@generate_password') ?>",
                    dataType: "json",
                    async: false,
                    success: function(sData) {
                        if (sData.password != undefined) {
                            $('#tbl_ligue_password').val(sData.password);
                        } else {
                            alert('Error server');
                        }
                    },
                    error: function (sData) {
                       alert('Error server');
                    }
                });
            }
            return false;
        });
    });
</script>
<div>
<div class="sb_bouton_a">
    <a href="#" id="generate_password">Generer un mot de passe</a>
</div>
</div>