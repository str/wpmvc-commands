<?php

namespace WPMVC\Commands\Traits;

use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to create views in a commad.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
trait CreateViewTrait
{
    /**
     * Registers and creates new widget.
     * @since 1.0.0
     *
     * @param string $key  View key/name.
     * @param array  $args Command arguments.
     */
    protected function createView($key, $args = [])
    {
        try {
            // Prepare
            $path = $this->rootPath.'/assets/views';
            $views = explode('.', $key);
            // Loop creation
            for ($i = 0; $i < count($views); ++$i) {
                if (count($views) - 1 == $i) {
                    // File check
                    $filename = $path.'/'.$views[$i].'.php';
                    if (!file_exists($filename))
                        file_put_contents(
                            $filename,
                            preg_replace('/\{0\}/', $key, $this->getTemplate('view.php'))
                        );
                } else {
                    // Directory check
                    $path .= '/'.$views[$i];
                    if (!is_dir($path))
                        mkdir($path);
                }
            }
            // Print end
            $this->_print('View created!');
            $this->_lineBreak();
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error ocurred.');
        }
    }
}