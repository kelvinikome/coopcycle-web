Feature: Dispatch

  Scenario: Retrieve task lists
    Given the fixtures file "dispatch.yml" is loaded
    And the user "sarah" has role "ROLE_COURIER"
    And the user "bob" has role "ROLE_ADMIN"
    And the user "bob" is authenticated
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And the user "bob" sends a "GET" request to "/api/task_lists?date=2018-12-01"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should match:
      """
      {
        "@context":"/api/contexts/TaskList",
        "@id":"/api/task_lists",
        "@type":"hydra:Collection",
        "hydra:member":[
          {
            "@id":"/api/task_lists/1",
            "@type":"TaskList",
            "items":[
              {
                "@id":"/api/tasks/1",
                "@type":"Task",
                "id":1,
                "type":"DROPOFF",
                "status":"TODO",
                "address":{
                  "@id":"/api/addresses/1",
                  "@type":"http://schema.org/Place",
                  "firstName":null,
                  "lastName":null,
                  "description":null,
                  "floor":null
                },
                "doneAfter":"2018-12-01T10:30:00+01:00",
                "doneBefore":"2018-12-01T11:00:00+01:00",
                "comments":null,
                "events":@array@,
                "updatedAt":"@string@.isDateTime()",
                "isAssigned":true,
                "assignedTo":"sarah",
                "previous":null,
                "next":null,
                "deliveryColor":null,
                "group":null,
                "tags":@array@,
                "position":0
              },
              {
                "@id":"/api/tasks/2",
                "@type":"Task",
                "id":2,
                "type":"DROPOFF",
                "status":"TODO",
                "address":{
                  "@id":"/api/addresses/2",
                  "@type":"http://schema.org/Place",
                  "firstName":null,
                  "lastName":null,
                  "description":null,
                  "floor":null
                },
                "doneAfter":"2018-12-01T11:30:00+01:00",
                "doneBefore":"2018-12-01T12:00:00+01:00",
                "comments":null,
                "events":@array@,
                "updatedAt":"@string@.isDateTime()",
                "isAssigned":true,
                "assignedTo":"sarah",
                "previous":null,
                "next":null,
                "deliveryColor":null,
                "group":null,
                "tags":@array@,
                "position":1
              }
            ],
            "distance":3806,
            "duration":1106,
            "polyline":@string@,
            "createdAt":"@string@.isDateTime()",
            "updatedAt":"@string@.isDateTime()",
            "username":"sarah"
          }
        ],
        "hydra:totalItems":1,
        "hydra:view":{
          "@id":"/api/task_lists?date=2018-12-01",
          "@type":"hydra:PartialCollectionView"
        },
        "hydra:search":{
          "@type":"hydra:IriTemplate",
          "hydra:template":"/api/task_lists{?date}",
          "hydra:variableRepresentation":"BasicRepresentation",
          "hydra:mapping":[
            {
              "@type":"IriTemplateMapping",
              "variable":"date",
              "property":"date",
              "required":false
            }
          ]
        }
      }
      """

  @debug
  Scenario: Create task list
    Given the fixtures file "dispatch.yml" is loaded
    And the user "sarah" has role "ROLE_COURIER"
    And the user "bob" has role "ROLE_ADMIN"
    And the user "bob" is authenticated
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And the user "bob" sends a "POST" request to "/api/task_lists" with body:
      """
      {
        "date": "2018-12-03",
        "courier": "/api/api_users/2"
      }
      """
    Then the response status code should be 201
