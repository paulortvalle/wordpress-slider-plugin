<!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/css/uikit.min.css" />

<!-- UIkit JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/js/uikit-icons.min.js"></script>

<div class="wrap my-uk">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  	
  	<div class="uk-margin-small"></div>

  	<div class="uk-grid-match uk-child-width-1-1" uk-grid>
  		<div class="">
  			<div class="uk-card uk-card-default uk-card-body">
  				
  				<div class="" uk-grid>
				    <div class="uk-width-auto@s uk-flex uk-flex-middle">Select Slideshow:</div>

				    <?php $sliders = $this->all_yvr_sliders( 'title' ); ?>
				    <div class="uk-width-expand@m">
				    	<form>
						    <select class="uk-select">
						    	<?php foreach ($sliders as $slider) { ?>
						    		<option value="<?php echo $slider['id']; ?>"><?php echo $slider['title']; ?></option>
						    	<?php } ?>
						    </select>
						</form>
				    </div>
				    <div class="uk-width-auto@s uk-flex uk-flex-middle">OR</div>
				    <div class="uk-width-auto@s">
				    	<a href="<?php echo wp_nonce_url(admin_url("admin-post.php?action=yvrslider_create_slider"), "yvrslider_create_slider")?>"
				    		class="uk-button uk-button-primary" title="Create a new slideshow"><span uk-icon="plus"></span> Create a new slideshow</a>
				    </div>
				</div>

  			</div>
  		</div>
  	</div>

  	<div class="uk-grid-match uk-grid-small uk-text-center" uk-grid>
    <div class="uk-width-auto@m uk-visible@l uk-card-primary uk-card-body">
        <div class="uk-card ">auto@m<br>visible@l</div>
    </div>
    <div class="uk-width-1-3@m uk-card-primary uk-card-body">
        <div class="uk-card ">1-3@m</div>
    </div>
    <div class="uk-width-expand@m uk-card-primary uk-card-body">
        <div class="uk-card ">expand@m</div>
    </div>
</div>



    <div class="uk-card uk-card-body uk-card-primary">
    	<div uk-grid>
		    <div>as</div>
		    <div>ss</div>
		</div>

        <h3 class="uk-card-title">Example headline</h3>

        <button class="uk-button uk-button-default" uk-tooltip="title: Hello World">Hover</button>
    </div>

  	<div>
	    <ul class="uk-child-width-expand" uk-switcher="connect: .my-switcher" uk-tab>
	        <li class="uk-active"><a href="#">Type of Slider</a></li>
	        <li><a href="#">sxx</a></li>
	        <li><a href="#">Item</a></li>
	        <li><a href="#">Item</a></li>
	    </ul>
	</div>

	<ul class="uk-switcher uk-margin my-switcher">
	    <!-- Slider Type -->
	    <li>

	    	<h3>Type of Slider</h3>
	    	<p>PRTV Slider works with Bootstrap or UIKit</p>

	    	<form class="uk-form-stacked" method="post" action="config-save.php">
	    		<?php settings_fields( 'prtv-slider' ); ?>

			    <fieldset class="uk-fieldset">

			        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
			            <label><input class="uk-radio" type="radio" name="radio2" checked> UI Kit 3</label>
			            <label><input class="uk-radio" type="radio" name="radio2"> Bootstrap 4</label>
			        </div>

			    </fieldset>

			    <p uk-margin>
				    <button class="uk-button uk-button-primary">Save</button>
				    <?php submit_button(); ?>
				</p>

			</form>
	    
	    </li>
	    <!-- Slider Type -->

	    <li>Hello again! <a href="#" uk-switcher-item="next">Next item</a></li>
	    <li>Bazinga! <a href="#" uk-switcher-item="previous">Previous item</a></li>
	</ul>

</div>

<style>
	/* Fixing errors on UIKit on Wordpress Admin */
	.my-uk a:focus {box-shadow: none !important;}
	.my-uk li {margin-bottom: 0 !important;}
</style>