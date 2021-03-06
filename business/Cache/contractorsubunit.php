<?php

namespace AppAnest\Cache;

use AppAnest\Model\contractorsubunit as Model;

class contractorsubunit extends \Smart\Data\Cache {

    public function selectCode(array $data) {
        $query = $data['query'];
        $start = $data['start'];
        $limit = $data['limit'];
        $proxy = $this->getStore()->getProxy();

        $sql = "
            select
                csu.id,
                csu.reserved,
                :contractorunitid as contractorunitid,
                etl.code as subunit,
                etl.description as subunitdescription,
                case coalesce(csu.id,0) when 0 then 0 else 1 end as isactive
            from
                enumtype et
                inner join enumtypelist etl on ( etl.enumtypeid = et.id )
                left join contractorsubunit csu on ( csu.subunit = etl.code and csu.contractorunitid = :contractorunitid )
            where et.name = 'subunit'
              and etl.code not in ( select subunit from contractorsubunit where subunit = etl.code and reserved = 1 and contractorunitid != :contractorunitid )
            order by etl.description";

        try {
            $pdo = $proxy->prepare($sql);

            $pdo->bindValue(":contractorunitid", "$query", \PDO::PARAM_INT);

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

    public function selectCodeAdditive(array $data) {
        $additiveid = $data['additiveid'];
        $contractorunitid = $data['contractorunitid'];

        $proxy = $this->getStore()->getProxy();

        $sql = "
            select
                csu.id,
                :contractorunitid as contractorunitid,
                etl.code as subunit,
                etl.description as subunitdescription,
                (
                    select
                        coalesce(sum(
                            coalesce(a.amountsun,0) + coalesce(a.amountmon,0) +
                            coalesce(a.amounttue,0) + coalesce(a.amountwed,0) +
                            coalesce(a.amountthu,0) + coalesce(a.amountfri,0) + coalesce(a.amountsat,0)
                        ),0) as total
                    from
                        additiveshift a
                    where a.additiveid = :additiveid
                      and a.contractorsubunitid = csu.id
                ) as shiftstotal
            from
                enumtype et
                inner join enumtypelist etl on ( etl.enumtypeid = et.id )
                inner join contractorsubunit csu on ( csu.subunit = etl.code and csu.contractorunitid = :contractorunitid )
            where et.name = 'subunit'
              and csu.id != 0
            order by etl.code, etl.description";

        try {
            $pdo = $proxy->prepare($sql);

            $pdo->bindValue(":additiveid", $additiveid, \PDO::PARAM_INT);
            $pdo->bindValue(":contractorunitid", $contractorunitid, \PDO::PARAM_INT);

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