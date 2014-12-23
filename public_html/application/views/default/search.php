<?if(isset($_GET['s']))
{
	echo '<p>Your results for <strong>'.$_GET['s'].'</strong>...</p>';
}?>

<script>

$(document).ready(function() {
    var track_load = 0; //total loaded record group(s)
    var loading  = false; //to prevents multipal ajax loads
    
    $('#results').load("/search/ajax/0/?s=<?=$_GET['s']?>", function() {track_load += 12;}); //load first group
    
    $(window).scroll(function() { //detect page scroll
        
        if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
        {
            
            if(track_load <= 1000 && loading==false) //there's more data to load
            {
                loading = true; //prevent further ajax loading
                
                //load data from the server using a HTTP POST request
                $.get('/search/ajax/'+ track_load + '/?s=<?=$_GET['s']?>', function(data){
                
                
                	if(data != "")
                    {      
						$("#results").append(data); //append received data into the element
						track_load += 12; //loaded group increment
						loading = false;                     
                    }
                    

                
                }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
                    
                    alert(thrownError); //alert with HTTP error
                    loading = false;
                
                });
                
            }
        }
    });
});
</script>


<div class="container">
	<div class="row">
		<div id="results">

		</div>
	</div>
</div>