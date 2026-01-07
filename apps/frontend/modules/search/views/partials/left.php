<style>
    li b {
        color: black;
    }

	#sort_buffer {
		min-height: 30px;
		list-style: none;
	}
</style>


<script type="text/javascript">
    $(document).ready(function(){
        $('.areg').click(function(){
            $('.areg').removeClass('bold');
            var id = this.id;
            $(this).hide();
            if (id=='az') {
                $('#za').show(); 
                $('#za').addClass('bold');               
            } else if (id=='za') {
                $('#az').show();
                $('#az').addClass('bold');                
            } else if (id=='unrate') {
                $('#rate').show();
                $('#rate').addClass('bold');                
            } else if (id=='rate') {
                $('#unrate').show();
                $('#unrate').addClass('bold');
            }
            
            $('.dreg').hide();
            $('#ul_'+id).show();
        });
    });
</script>
