# Query language parser

Thanks to this library parsing custom query language in PHP is a piece of cake for you. Just define your own fields, operators and values and everything else will work out of the box for you. So would you like to have query language like this?

```
(brand = bmw AND color = black) OR brand IN (audi, skoda)
```

This library works on top of the ferno/loco parser. See https://github.com/qntm/loco for more details. Thanks for this great library!

## How it works

You will define supported fields and operators, and also possible relations of those two. And then for every possible relation you will define what's the output of the parsing.

Thanks to this library you are able to transform you query language into set of objects that represent database query, or into SQL like syntax. It's completely up to you what will be the output.

## Language parts

### Logical operators

This library includes standard logical operators out of the box. More concretely we are talking about these:

1. AND
    ```
    brand = bmw AND color = black
    ```

2. OR
    ```
    color = black OR brand IN (audi, skoda)
    ```

3. NOT
    ```
    NOT (brand = bmw AND color = black)
    ```

You can also adjust operators priority by adding brackets like this:
```
(brand = bmw AND color = black) OR (brand = audi AND color = white)
```

### Relation operators

The most common relation operators are already part of the library. You can use operators `=`, `!=`, `~`, `!~`, `<`, `<=`, `>`, `>=`, `LIKE`, `NOT LIKE`, `IN`, `NOT IN`, `IS NULL` and `IS NOT NULL` without any special effort.

#### Custom operators

To enrich query language with your own relation operator you must do these two steps:

1. Create interface defining contract for fields using this operator. See `QueryLanguageFieldSupportingEqualToOperator` for an example.

    Generally it should define two things:
    
    * Form of the value that should follow the operator - you can define this by extending certain interface. In case of operator without value (eg. `IS NULL`) don't extend anything special. In case of operator with single value (eg. `=`) extend `QueryLanguageFieldSupportingSingleValueOperator` interface. In case multiple values should follow after the operator, extend interface `QueryLanguageFieldSupportingMultipleValuesOperator`.
    
    * Method that will receive parsed field name and eventually parsed value and will return output value for this certain field and operator combination.
    
2. Class representing the operator. It must implement interface `QueryLanguageOperator`. See eg. `EqualToQueryLanguageOperator` for an example.

    It must implement these methods:
    
    * `getOperatorIdentifier` - returns string identifier of the operator
    * `createOperatorParser` - returns parser for the operator
    * `isFieldSupported` - returns if the given field is supported by the operator. Typically you will test if the given field implements interface created in step 1.
    * `createFieldExpressionParser` - creates parser for combination of given field and the operator

### Fields

Fields are core of your query language. It defines domain your query language works in.

Every field you want to support in the query language is defined by a class implementing interface `QueryLanguageField`. This interface defines 3 basic methods:

* `getFieldIdentifier` - returns string identifier of the field
* `getFieldNameParserIdentifier` - returns string identifier of field name parser
* `createFieldNameParser` - returns field name parser

Every field should also implement all interfaces of the relation operators that it supports (eg. `QueryLanguageFieldSupportingEqualToOperator` for support of equal to operator).

## Query language parser

To create query language parser use `QueryParserFactory`. Just pass list of your fields, list of supported relation operators and output definition for logical operators (implement `LogicalOperatorOutputFactory` interface for this) and your query language parser should be ready to use! 

## Example

See examples dir in this repository for example car-based query language.
