<?php

namespace Zedrox\ImageSlider\Model;

use Magento\Framework\Exception\LocalizedException;

class ImageUploader
{
     /**
     * @var string
     */
    const IMAGE_TMP_PATH = 'slider/tmp/image';
    /**
     * @var string
     */
    const IMAGE_PATH = 'slider/image';
    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $coreFileStorageDatabase;

    /**
     * Media directory object (writable).
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $_uploaderFactory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Base tmp path
     *
     * @var string
     */
    protected $baseTmpPath;

    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Allowed extensions
     *
     * @var string
     */
    protected $allowedExtensions;

    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $_uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        $baseTmpPath = self::IMAGE_TMP_PATH,
        $basePath = self::IMAGE_PATH,
        $allowedExtensions = []
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_uploaderFactory = $_uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Set base tmp path
     *
     * @param string $baseTmpPath baseTmpPath
     *
     * @return void
     */
    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * Set base path
     *
     * @param string $basePath basePath
     *
     * @return void
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Set allowed extensions
     *
     * @param string[] $allowedExtensions allowedExtensions
     *
     * @return void
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Retrieve base tmp path
     *
     * @return string
     */
    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * @param $fileName
     * @param string $action
     * @param null $newFileName
     * @throws LocalizedException
     */
    public function moveCopyFileFromTmp($fileName, $action = 'move', $newFileName = null) {
        switch ($action) {
            case 'move':
                $this->moveFileFromTmp($fileName, $newFileName);
                break;

            case 'copy':
                $this->copyFileFromTmp($fileName, $newFileName);
                break;
        }
    }
    /**
     * Checking file for moving and move it
     *
     * @param $fileName
     * @param null $newFileName
     * @return string
     *
     * @throws LocalizedException
     */
    public function copyFileFromTmp($fileName, $newFileName = null)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseFilePath = $this->getFilePath($basePath, (empty($newFileName) ? $fileName : $newFileName));
        $baseTmpFilePath = $this->getFilePath($baseTmpPath, $fileName);

        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpFilePath,
                $baseFilePath
            );
            $this->mediaDirectory->copyFile(
                $baseTmpFilePath,
                $baseFilePath
            );
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).' . $e->getMessage())
            );
        }

        return $fileName;
    }

    /**
     * Checking file for moving and move it
     *
     * @param string $imageName imageName
     *
     * @param null $newFileName
     * @return string
     *
     * @throws LocalizedException
     */
    public function moveFileFromTmp($imageName, $newFileName = null)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseImagePath = $this->getFilePath($basePath, (empty($newFileName) ? $imageName : $newFileName));
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            $this->coreFileStorageDatabase->copyFile($baseTmpImagePath, $baseImagePath);
            $this->mediaDirectory->renameFile($baseTmpImagePath, $baseImagePath);
        } catch (\Exception $e) {
            throw new LocalizedException(__('Something went wrong while saving the file(s).'));
        }

        return $imageName;
    }
    /**
     * Retrieve base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Retrieve path
     *
     * @param string $path      path
     *
     * @param string $imageName imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }


     /**
     * Retrieve base path
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }
    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId fileId
     *
     * @return string[]
     *
     * @throws LocalizedException
     */
    public function saveFileToTmpDir($fileId)
    {
        $baseTmpPath = $this->getBaseTmpPath();

        $uploader = $this->_uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowRenameFiles(true);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));

        if (!$result) {
            throw new LocalizedException(__('File can not be saved to the destination folder.'));
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['name'] = $result['file'];
        $result['url'] = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->getFilePath($baseTmpPath, $result['file']);

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch(\Exception $e) {
                throw new LocalizedException(__('Something went wrong while saving the file(s).'));
            }
        }

        return $result;
    }

    public function getMediaFilePath(){
        return $this->mediaDirectory->getAbsolutePath($this->getBasePath());
    }
}
