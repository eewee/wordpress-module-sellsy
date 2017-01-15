<?php
if( !class_exists('Form_TicketSearch')){
	class Form_TicketSearch extends WP_Query{
		
		private $_action;
		
		function __construct(){
			$this->_action = $_SERVER["REQUEST_URI"];
		}
		
		/**
		 * retourn form
 		 * @param $_POST $p
		 */
		public function search( $p ){ ?>
			
			<form method="post" action="<?php echo $this->_action; ?>">
		
				<div class="postbox " id="postexcerpt">
					<h3 class="hndle"><span><?php _e('Search', PLUGIN_NOM_LANG); ?></span></h3>
					<div class="inside">
					
                        <table class='table1'>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="formPlatSearch_nom" value="<?php echo $p['formPlatSearch_nom']; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Ingredient', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="formPlatSearch_ingredient" value="<?php echo $p['formPlatSearch_ingredient']; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Category', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $t_plat_categorie	= new TPlatCategorie();
                                    $platCategories  	= $t_plat_categorie->getPlatCategories();
                                    ?><select name='formPlatSearch_categorie'>
                                        <option></option>
                                        <?php
                                        foreach( $platCategories as $platCategorie ){
                                            if( $platCategorie->ID_PLAT_CATEGORIE == $p['formPlatSearch_categorie'] ){
                                                    $selected = "selected";
                                            }else{
                                                    $selected = "";
                                            }

                                            ?><option value="<?php echo $platCategorie->ID_PLAT_CATEGORIE; ?>" <?php echo $selected; ?>><?php echo $platCategorie->NOM; ?></option><?php
                                        }
                                    ?></select>
                                </td>
                            </tr>
                        </table>
                                                
                        <p>
							<div class="submit">
					            <input type="submit" name="btn_formPlatSearch" value="<?php _e('Validate the research', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
					        </div>
						</p>
						
					</div>
				</div>
		
			</form>
			
		<?php
		}
		
	}
}