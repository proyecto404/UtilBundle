<?php

namespace Proyecto404\UtilBundle\Doctrine\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Query\ResultSetMappingBuilder as DoctrineResultSetMappingBuilder;

/**
 * Class ResultSetMappingBuilder
 */
class ResultSetMappingBuilder extends DoctrineResultSetMappingBuilder
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     * @param integer       $defaultRenameMode
     */
    public function __construct(EntityManager $em, $defaultRenameMode = DoctrineResultSetMappingBuilder::COLUMN_RENAMING_NONE)
    {
        parent::__construct($em, $defaultRenameMode);

        $this->em = $em;
    }

    /**
     * @param string $class
     * @param string $alias
     * @param array  $renamedColumns
     * @param null   $resultAlias
     */
    public function addRootEntityFromClassMetadata($class, $alias, $renamedColumns = array(), $resultAlias = null)
    {
        $this->addEntityResult($class, $alias, $resultAlias);
        $this->addAllClassFields($class, $alias, $renamedColumns);
    }

    /**
     * Adds all fields of the given class to the result set mapping (columns and meta fields)
     *
     * @param string $class
     * @param string $alias
     * @param array  $renamedColumns
     */
    protected function addAllClassFields($class, $alias, $renamedColumns = array())
    {
        $classMetadata = $this->em->getClassMetadata($class);
        if ($classMetadata->isInheritanceTypeSingleTable() || $classMetadata->isInheritanceTypeJoined()) {
            throw new \InvalidArgumentException('ResultSetMapping builder does not currently support inheritance.');
        }
        $platform = $this->em->getConnection()->getDatabasePlatform();
        foreach ($classMetadata->getColumnNames() as $columnName) {
            $propertyName = $classMetadata->getFieldName($columnName);
            if (isset($renamedColumns[$columnName])) {
                $columnName = $renamedColumns[$columnName];
            }
            $columnName = $platform->getSQLResultCasing($columnName);
            if (isset($this->fieldMappings[$columnName])) {
                throw new \InvalidArgumentException("The column '$columnName' conflicts with another column in the mapper.");
            }
            $this->addFieldResult($alias, $columnName, $propertyName);
        }
        foreach ($classMetadata->associationMappings as $associationMapping) {
            if ($associationMapping['isOwningSide'] && $associationMapping['type'] & ClassMetadataInfo::TO_ONE) {
                foreach ($associationMapping['joinColumns'] as $joinColumn) {
                    $columnName = $joinColumn['name'];
                    $renamedColumnName = isset($renamedColumns[$columnName]) ? $renamedColumns[$columnName] : $columnName;
                    $renamedColumnName = $platform->getSQLResultCasing($renamedColumnName);
                    if (isset($this->metaMappings[$renamedColumnName])) {
                        throw new \InvalidArgumentException("The column '$renamedColumnName' conflicts with another column in the mapper.");
                    }

                    $this->addMetaResult(
                        $alias,
                        $renamedColumnName,
                        $columnName,
                        in_array($columnName, $classMetadata->getIdentifierColumnNames())
                    );
                }
            }
        }
    }
}
