<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

use Dropbox\Client;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

return [
    'file-manager' => array(
        'filesystems' => [
            'factories' => array(
                'dropbox' => function($sm) {
                        $client = new Client('1RHCVsq2P9sAAAAAAAAADeE6_ujjcNAXq72pNvOkm1TC8FSno--g3FS_sjujrJs1', 'myapp.local');
                        $filesystem = new Filesystem(new \League\Flysystem\Adapter\Dropbox($client, '/test'));
                        return $filesystem;
                    },
                'protected' => function($sm) {
                        $ins = new Filesystem(new Local('data/file-manager'));

                        class sss implements \League\Flysystem\PluginInterface {
                            protected $filesystem;

                            public function handle($path = null)
                            {
                                return '/file-provider/';
                            }

                            /**
                             * Get the method name
                             *
                             * @return  string
                             */
                            public function getMethod()
                            {
                                return 'getUrl';
                            }

                            /**
                             * Set the Filesystem object
                             *
                             * @param  \League\Flysystem\FilesystemInterface $filesystem
                             */
                            public function setFilesystem(\League\Flysystem\FilesystemInterface $filesystem)
                            {
                                $this->filesystem = $filesystem;
                            }
                        }

                        $ins->addPlugin(new sss());
                        
                        return $ins;
                    },
                'public' => function($sm) {
                    $ins =  new Filesystem(new Local('public/var/files'));

                    class sss implements \League\Flysystem\PluginInterface {
                        protected $filesystem;

                        public function handle($path = null)
                        {
                            return 'http://myapp.local/var/files/' . $path;
                        }

                        /**
                         * Get the method name
                         *
                         * @return  string
                         */
                        public function getMethod()
                        {
                            return 'getUrl';
                        }

                        /**
                         * Set the Filesystem object
                         *
                         * @param  \League\Flysystem\FilesystemInterface $filesystem
                         */
                        public function setFilesystem(\League\Flysystem\FilesystemInterface $filesystem)
                        {
                            $this->filesystem = $filesystem;
                        }
                    }

                    $ins->addPlugin(new sss());
                    
                    return $ins;
                }
            ),
        ]
    ),
];