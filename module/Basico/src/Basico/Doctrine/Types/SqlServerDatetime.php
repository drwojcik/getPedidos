<?php
namespace Basico\Doctrine\Types;

use Doctrine\DBAL\Types\ConversionException;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SqlServerDatetime extends Type {

    const MYTYPE = 'SqlServerDatetime'; // modify to match your type name
    
    
    public function getName() {
        return self::MYTYPE;
    }
    
    
    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform) {
        return null;
    }
    
    
    /**
     * @param Datetime $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        return ($value !== null)
            ? $value->format('Y-m-d H:i:s.000') : null;
    }
    
    
    /**
     * @param string $value
     */    
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        if($value == null)
            return null;

        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s.u', $value);        
        if (!$datetime){
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        return $datetime;
    }               
}

?>