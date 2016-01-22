<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class View
 */
class View extends Kohana_View
{
    public static $template_name = '';

    public static $buffers = array();

    /**
     * Sets the view filename.
     *
     *     $view->set_filename($file);
     *
     * @param   string  view filename
     *
     * @return  View
     * @throws  View_Exception
     */
    public function set_filename($file)
    {
        if (($path = Kohana::find_file('views', $file)) === FALSE)
        {
            if (($path = Kohana::find_file('views', 'template/' . self::$template_name . '/' . $file)) === FALSE)
            {
                if (($path = Kohana::find_file('views', 'template/' . self::$template_name . '/modules/' . $file)) === FALSE)
                {
                    if (($path = Kohana::find_file('views', 'template/default/' . $file)) === FALSE)
                    {
                        if (($path = Kohana::find_file('views', 'template/default/modules/' . $file)) === FALSE)
                        {
                            throw new View_Exception('The requested view :file could not be found (template :template)', array(
                                ':file' => $file,
                                ':template' => self::$template_name,
                            ));
                        }
                    }
                }
            }
        }

        // Store the file path locally
        $this->_file = $path;

        return $this;
    }

}
