<?php
namespace KapFileManager\V1\Rpc\FilesystemSync;

class FilesystemSyncControllerFactory
{
    public function __invoke($controllers)
    {
        return new FilesystemSyncController(
            $controllers->getServiceLocator()->get('KapFileManager\\FilesystemManager'),
            $controllers->getServiceLocator()->get('KapFileManager\\FileRepository')
        );
    }
}
