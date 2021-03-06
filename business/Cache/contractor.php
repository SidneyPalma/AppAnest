<?php

namespace AppAnest\Cache;

use AppAnest\Model\contractor as Model;

class contractor extends \Smart\Data\Cache {

    public function selectLike(array $data) {
        $p = $f = array();
        $query = $data['query'];
        $start = $data['start'];
        $limit = $data['limit'];
        $params = json_decode($data['params']);
        $proxy = $this->getStore()->getProxy();
        $notate = $this->getStore()->getModel()->getNotate();
        $fields = (isset($data['fields']) && count(json_decode($data['fields'])) !== 0) ? json_decode($data['fields']) : self::objectToArray($notate->property);

        // set fields
        foreach ($fields as $key => $value) {
            $f[] = $value;
        }

        // set params
        foreach ($params as $key => $value) {
            $p[] = "$value LIKE :$value";
        }

        $sql = "SELECT " .implode(',', $f). " FROM person WHERE typeperson = 'C' and ( " . implode(' OR ', $p) . " )";

        try {
            $pdo = $proxy->prepare($sql);

            foreach ($params as $key => $value) {
                $pdo->bindValue(":$value", "$query%", \PDO::PARAM_STR);
            }

            $pdo->execute();
            $rows = $pdo->fetchAll();

            self::_setRows($rows);
            self::_setPage($start,$limit);

        } catch ( \PDOException $e ) {
            self::_setSuccess(false);
            self::_setText($e->getMessage());
        }

        return self::getResultToJson();
    }

    public function selectCode(array $data) {
        $query = $data['query'];
        $proxy = $this->getStore()->getProxy();

        $sql = "
            SELECT
                p.*,
                c.*,
                getEnum('addressfederationunit', p.addressfederationunit) as addressfederationunitdescription
            FROM
                person p
                inner join contractor c on ( c.id = p.id )
            WHERE c.id = :id";

        try {
            $pdo = $proxy->prepare($sql);

            $pdo->bindValue(":id", "$query", \PDO::PARAM_INT);

            $pdo->execute();
            $rows = $pdo->fetchAll();

            self::_setRows($rows);

        } catch ( \PDOException $e ) {
            self::_setSuccess(false);
            self::_setText($e->getMessage());
        }

        return self::getResultToJson();
    }

}