<?php
    $langArray = array(
        array(
        'name'=>"Afrikaans",
        'value'=>"af"
        ),

        array(
        'name'=>"Albanian",
        'value'=>"sq"
        ),
        
        array(
        'name'=>"Arabic",
        'value'=>"ar"
        ),

        array(
        'name'=>"Azerbaijani",
        'value'=>"az"
        ),

        array(
        'name'=>"Bengali",
        'value'=>"bn"
        ),

        array(
        'name'=>"Bulgarian",
        'value'=>"bg"
        ),

        array(
        'name'=>"Catalan",
        'value'=>"ca"
        ),

        array(
        'name'=>"Chinese (CHN)",
        'value'=>"zh_CN"
        ),

        array(
        'name'=>"Chinese (HKG)",
        'value'=>"zh_HK"
        ),

        array(
        'name'=>"Chinese (TAI)",
        'value'=>"zh_TW"
        ),

        array(
        'name'=>"Croatian",
        'value'=>"hr"
        ),

        array(
        'name'=>"Czech",
        'value'=>"cs"
        ),

        array(
        'name'=>"Danish",
        'value'=>"da"
        ),

        array(
        'name'=>"Dutch",
        'value'=>"nl"
        ),

        array(
        'name'=>"English",
        'value'=>"en"
        ),

        array(
        'name'=>"English (UK)",
        'value'=>"en_GB"
        ),

        array(
        'name'=>"English (US)",
        'value'=>"en_US"
        ),

        array(
        'name'=>"Estonian",
        'value'=>"et"
        ),

        array(
        'name'=>"Filipino",
        'value'=>"fil"
        ),

        array(
        'name'=>"Finnish",
        'value'=>"fi"
        ),

        array(
        'name'=>"French",
        'value'=>"fr"
        ),

        array(
        'name'=>"Georgian",
        'value'=>"ka"
        ),

        array(
        'name'=>"German",
        'value'=>"de"
        ),

        array(
        'name'=>"Greek",
        'value'=>"el"
        ),

        array(
        'name'=>"Gujarati",
        'value'=>"gu"
        ),

        array(
        'name'=>"Hausa",
        'value'=>"ha"
        ),
        
        array(
        'name'=>"Hebrew",
        'value'=>"he"
        ),

        array(
        'name'=>"Hindi",
        'value'=>"hi"
        ),

        array(
        'name'=>"Hungarian",
        'value'=>"hu"
        ),

        array(
        'name'=>"Indonesian",
        'value'=>"id"
        ),

        array(
        'name'=>"Irish",
        'value'=>"ga"
        ),

        array(
        'name'=>"Italian",
        'value'=>"it"
        ),

        array(
        'name'=>"Japanese",
        'value'=>"ja"
        ),

        array(
        'name'=>"Kannada",
        'value'=>"kn"
        ),
        
        array(
        'name'=>"Kazakh",
        'value'=>"kk"
        ),

        array(
        'name'=>"Kinyarwanda",
        'value'=>"rw_RW"
        ),

        array(
        'name'=>"Korean",
        'value'=>"ko"
        ),

        array(
        'name'=>"Kyrgyz (Kyrgyzstan)",
        'value'=>"ky_KG"
        ),

        array(
        'name'=>"Lao",
        'value'=>"lo"
        ),

        array(
        'name'=>"Latvian",
        'value'=>"lv"
        ),

        array(
        'name'=>"Lithuanian",
        'value'=>"lt"
        ),

        array(
        'name'=>"Macedonian",
        'value'=>"mk"
        ),

        array(
        'name'=>"Malay",
        'value'=>"ms"
        ),

        array(
        'name'=>"Malayalam",
        'value'=>"ml"
        ),

        array(
        'name'=>"Marathi",
        'value'=>"mr"
        ),

        array(
        'name'=>"Norwegian",
        'value'=>"nb"
        ),

        array(
        'name'=>"Persian",
        'value'=>"fa"
        ),

        array(
        'name'=>"Polish",
        'value'=>"pl"
        ),

        array(
        'name'=>"Portuguese (BR)",
        'value'=>"pt_BR"
        ),

        array(
        'name'=>"Portuguese (POR)",
        'value'=>"pt_PT"
        ),

        array(
        'name'=>"Punjabi",
        'value'=>"pa"
        ),

        array(
        'name'=>"Romanian",
        'value'=>"ro"
        ),

        array(
        'name'=>"Russian",
        'value'=>"ru"
        ),

        array(
        'name'=>"Serbian",
        'value'=>"sr"
        ),

        array(
        'name'=>"Slovak",
        'value'=>"sk"
        ),

        array(
        'name'=>"Slovenian",
        'value'=>"sl"
        ),

        array(
        'name'=>"Spanish",
        'value'=>"es"
        ),

        array(
        'name'=>"Spanish (ARG)",
        'value'=>"es_AR"
        ),

        array(
        'name'=>"Spanish (SPA)",
        'value'=>"es_ES"
        ),

        array(
        'name'=>"Spanish (MEX)",
        'value'=>"es_MX"
        ),

        array(
        'name'=>"Swahili",
        'value'=>"sw"
        ),

        array(
        'name'=>"Swedish",
        'value'=>"sv"
        ),

        array(
        'name'=>"Tamil",
        'value'=>"ta"
        ),

        array(
        'name'=>"Telugu",
        'value'=>"te"
        ),

        array(
        'name'=>"Thai",
        'value'=>"th"
        ),

        array(
        'name'=>"Turkish",
        'value'=>"tr"
        ),

        array(
        'name'=>"Ukrainian",
        'value'=>"uk"
        ),

        array(
        'name'=>"Urdu",
        'value'=>"ur"
        ),

        array(
        'name'=>"Uzbek",
        'value'=>"uz"
        ),

        array(
        'name'=>"Vietnamese",
        'value'=>"vi"
        ),

        array(
        'name'=>"Zulu",
        'value'=>"zu"
        ),
    );
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Wacr
 * @subpackage Wacr/admin/partials
 */     
?>

<div class="admin-menu-setting-wacr">
      <div class="tabset">
        <input type="radio" name="tabset" id="tab1" aria-controls="1" checked>
        <label class="wacrlabel" for="tab1">
          <?php esc_html_e('Create Template', 'wacr'); ?>
        </label>       
       
        <div class="tab-panels">
          <section id="1" class="tab-panel">
            <div>
                <div>
                    <label>
                        <b>
                        <?php esc_html_e("Template Name", 'wacr');?>
                        </b>
                    </label>
                </div>
                <div>
                    <input type="text" id="" placeholder="Enter header name"/>
                </div>
            </div>

            <br>
            <div>
                <div>
                    <label>
                        <b>
                        <?php esc_html_e("Template Language", 'wacr');?>
                        </b>
                    </label>
                </div>
                <div>
                    <select> 
                        <?php foreach($langArray as $key => $val){ ?>
                        <option value="<?php esc_attr_e($val["value"]);?>">
                            <?php echo esc_attr($val["name"], 'wacr');?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <br>
            <div>
                <div>
                    <label>
                        <b>
                        <?php esc_html_e("Header", 'wacr');?>
                        </b>
                    </label>
                </div>
                <div>
                   <input type="text"/>
                </div>
            </div>

            <br>
            <div>
                <div>
                    <label>
                        <b>
                        <?php esc_html_e("Body Text", 'wacr');?>
                        </b>
                    </label>
                </div>
                <div>
                   <textarea>

                   </textarea>
                </div>
            </div>

            <br>
            <div>
                <div>
                    <label>
                        <b>
                        <?php esc_html_e("Footer", 'wacr');?>
                        </b>
                    </label>
                </div>
                <div>
                   <textarea>
                    
                   </textarea>
                </div>
            </div>


            <br>
            <div>
                <div>
                    <label>
                        <b>
                        <?php esc_html_e("Button", 'wacr');?>
                        </b>
                    </label>
                </div>
                <div>
                   <select>
                    <option value="-1"> <?php esc_html_e("None", 'wacr');?></option>
                    <option value="1"> <?php esc_html_e("Call to Action", 'wacr');?></option>
                    <option value="2"> <?php esc_html_e("Marketing", 'wacr');?></option>
                   </select>
                </div>
            </div>
            

          </section>
          
         
        </div>
      </div>
    </div>
