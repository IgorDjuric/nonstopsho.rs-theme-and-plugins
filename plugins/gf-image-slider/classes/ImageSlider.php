<?php

namespace GfImageSlider;
class ImageSlider
{
    protected $width;
    protected $height;
    protected $options;

    /**
     * ImageSlider constructor.
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->init();

    }

    public function init()
    {
        add_shortcode('gfImageSlider', array($this, 'imageCarousel'));
    }

    //Name is name of input inside setting array
    public function imageUploadField($name)
    {
        $options = get_option('gf-image-values');
        if (!empty($options[$name]['id'])) {
            $image_attributes = wp_get_attachment_image_src($options[$name]['id'], array($this->width, $this->height));
            $src = $image_attributes[0];
            $value = $options[$name]['id'];

        } else {
            $src = '';
            $value = '';
        }
        if (!empty($options[$name]['link'])) {
            $link = $options[$name]['link'];
        } else{
            $link = '';
        }
        echo '
        <div class="upload-image-wrapper">
            <img src="' . $src . '" width="' . $this->width . 'px" height="' . $this->height . 'px" />
            <div>
                <input type="hidden" name="gf-image-values[' . $name . '][id]" id="gf-image-values[' . $name . '][id]" value="' . $value . '" />
                 <button type="submit" class="upload-image-button button">Izaberite sliku</button>
                <button type="submit" class="remove-image-button button">Obrišite sliku</button>
                <input type="text" name="gf-image-values[' . $name . '][link]" id="gf-image-values[' . $name . '][link]" value="' . $link . '" />
            </div>
        </div>
    ';
    }

    public function imageCarousel()
    {
        $options = get_option('gf-image-values');
        $class = 'active';
        $i = 0;
        require(__DIR__ . "/../html/carouselHeader.html");
        foreach ($options as $option) {;
                if (empty($option['id'])) {
                    continue;
                }
                $image_attributes = wp_get_attachment_image_src($option['id'], array($this->width, $this->height));
                $src = $image_attributes[0];
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