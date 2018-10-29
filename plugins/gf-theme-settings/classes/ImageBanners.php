<?php

namespace GfThemeSettings;

class ImageBanners
{
    protected $options;

    public function __construct()
    {

        $this->init();
    }

    public function init()
    {
        add_shortcode('gfImageBanners', array($this, 'imageBanners'));
    }

    public function imageUploadField($name)
    {
        $options = get_option('gf-image-banners-values');
        if (!empty($options[$name]['id'])) {
            $image_attributes = wp_get_attachment_image_src($options[$name]['id'], 'full');
            $src = $image_attributes[0];
            $width = $image_attributes[1];
            $height = $image_attributes[2];
            $value = $options[$name]['id'];

        } else {
            $src = '';
            $width = '';
            $value = '';
        }
        if (!empty($options[$name]['link'])) {
            $link = $options[$name]['link'];
        } else {
            $link = '';
        }

        echo '
        <div class="upload-image-banners-wrapper">
            <img src="' . $src . '" width="' . $width . '" height="auto"/>
            <div>
                <input type="hidden" name="gf-image-banners-values[' . $name . '][id]" id="gf-image-banners-values[' . $name . '][id]" value="' . $value . '" />
                 <button type="button" class="upload-image-banners-button button">Izaberite sliku</button>
                <button type="submit" class="upload-image-banners-button">Obrišite sliku</button>
                <input type="text" name="gf-image-banners-values[' . $name . '][link]" id="gf-image-banners-values[' . $name . '][link]" value="' . $link . '" placeholder="Link" />
            </div>
        </div>';
    }

    public function imageBanners()
    {
        $options = get_option('gf-image-banners-values');
        if (!empty($options)) {
            $i = 1;
            echo '<div class="row gf-image-banners">';
            foreach ($options as $option) {
                ;
                if (empty($option['id'])) {
                    continue;
                }
                $image_attributes = wp_get_attachment_image_src($option['id'], 'full');
                $src = $image_attributes[0];
                $width = $image_attributes[1];
                $link = $option['link'];
                if ($i == 1 || $i % 3 == 1) {
                    $class = 'row gf-image-banners-wider';
                } else {
                    $class = 'col-6 gf-image-banners__item';
                }

                require(__DIR__ . "/../html/imageBanners.phtml");
                $i++;
            }
            echo '</div>';
        }
    }
}