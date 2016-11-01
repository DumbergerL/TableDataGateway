# TableDataGateway
PHP Interface-Class for an MySQL DB

  Author: Lukas Dumberger
  Version: 2016-10-25
  Important!: DataTable needs an Primary Key called [>TableName<]ID (e.g.: CustomerID)

TableDataGateway-Methods:
	constructor(pdo :Object, tableName :String, columnName :String[])
	Get(id :Int) :String[]
	GetAll(columnName :String) :String[]
	Add(data :Array[]) :Bool
	Del(id :Int) :Bool
	Set(data :Array[], id :Int) :Bool
	Search(columnName :String, SearchTerm :String) :Array[][]
	CountRows() :Int 
  
