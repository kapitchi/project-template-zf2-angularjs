<?php
namespace KapFileManager\V1\Rpc\FileAccess;

class FileAccessControllerFactory
{
    public function __invoke($controllers)
    {
        return new FileAccessController(
            $controllers->getServiceLocator()->get('KapFileManager\\FilesystemManager'),
            $controllers->getServiceLocator()->get('KapFileManager\\FileRepository')
        );
    }
}
