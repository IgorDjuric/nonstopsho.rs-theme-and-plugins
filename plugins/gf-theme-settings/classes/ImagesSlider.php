<?php

namespace GfThemeSettings;

class ImagesSlider
{
    protected $options;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        add_shortcode('gfImageSlider', array($this, 'imageCarousel'));
    }

    //Name is name of input inside setting array
    public function imageUploadField($name)
    {
        $options = get_option('gf-image-slider-values');
        if (!empty($options[$name]['id'])) {
            $image_attributes = wp_get_attachment_image_src($options[$name]['id'], 'full');
            $src = $image_attributes[0];
            $width = $image_attributes[1];
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
        <div class="upload-image-wrapper">
            <img src="' . $src . '" width="' . $width . '" height="auto" />
            <div>
                <input type="hidden" name="gf-image-slider-values[' . $name . '][id]" id="gf-image-slider-values[' . $name . '][id]" value="' . $value . '" />
                 <button type="button" class="upload-image-button button">Izaberite sliku</button>
                <button type="submit" class="remove-image-button button">Obrišite sliku</button>
                <input type="text" name="gf-image-slider-values[' . $name . '][link]" id="gf-image-slider-values[' . $name . '][link]" value="' . $link . '" />
            </div>
        </div>
    ';
    }

    public function imageCarousel()
    {
        $options = get_option('gf-image-slider-values');
        if (!empty($options)) {
            $class = 'active';
            $i = 0;
            require(__DIR__ . "/../html/carouselHeader.html");
            foreach ($options as $option) {
                ;
                if (empty($option['id'])) {
                    continue;
                }
                $image_attributes = wp_get_attachment_image_src($option['id'], 'full');
                $src = $image_attributes[0];
                $width = $image_attributes[1];
                $link = $option['link'];
                if ($i != 0) {
                    $class = '';
                }

                require(__DIR__ . "/../html/carouselImage.phtml");
                $i++;
            }
            require(__DIR__ . "/../html/carouselFooter.html");
        }
    }
}