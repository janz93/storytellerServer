<?php

namespace Storyteller\core\db;

interface Mysql {
  
  const SQL_AND           = 'AND';
  const SQL_ASC           = 'ASC';
  const SQL_AS            = 'AS';
  const SQL_DELETE        = 'DELETE';
  const SQL_DESC          = 'DESC';
  const SQL_DISTINCT      = 'DISTINCT';
  const SQL_FOR_UPDATE    = 'FOR UPDATE';
  const SQL_FROM          = 'FROM';
  const SQL_GROUP_BY      = 'GROUP BY';
  const SQL_HAVING        = 'HAVING';
  const SQL_INSERT        = 'INSERT INTO';
  const SQL_INSERT_VALUES = 'VALUES';
  const SQL_JOIN          = 'JOIN';
  const SQL_LEFT_JOIN     = 'LEFT JOIN';
  const SQL_LIMIT         = 'LIMIT';
  const SQL_OFFSET        = 'OFFSET';
  const SQL_ON            = 'ON';
  const SQL_OR            = 'OR';
  const SQL_ORDER_BY      = 'ORDER BY';
  const SQL_RIGHT_JOIN    = 'RIGHT JOIN';
  const SQL_SELECT        = 'SELECT';
  const SQL_UNION         = 'UNION';
  const SQL_UNION_ALL     = 'UNION ALL';
  const SQL_UPDATE        = 'UPDATE';
  const SQL_UPDATE_SET    = 'SET';
  const SQL_WILDCARD      = '*';
  const SQL_WHERE         = 'WHERE';
  
  public function finalQuery();
}