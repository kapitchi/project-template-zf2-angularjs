<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapFileManager;


use KapApigility\EntityRepositoryInterface;
use League\Flysystem\Filesystem;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use ZF\MvcAuth\Identity\IdentityInterface;

interface FileRepositoryInterface extends EntityRepositoryInterface {
    public function sync(FilesystemManager $manager, $filesystemName, IdentityInterface $identity = null, $path = null);
    public function createFileEntityFromPath(FilesystemManager $manager, $filesystemName, $path, IdentityInterface $identity);
} 