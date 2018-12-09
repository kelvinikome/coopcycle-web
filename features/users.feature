Feature: Users

  Scenario: Retrieve users filtered by role
    Given the fixtures file "users.yml" is loaded
    And the user "bob" has role "ROLE_ADMIN"
    And the user "sarah" has role "ROLE_COURIER"
    And the user "bob" is authenticated
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And the user "bob" sends a "GET" request to "/api/api_users?roles[]=ROLE_COURIER"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should match:
      """
      {
        "@context":"/api/contexts/ApiUser",
        "@id":"/api/api_users",
        "@type":"hydra:Collection",
        "hydra:member":[
          {
            "@id":"@string@.startsWith('/api/api_users')",
            "@type":"ApiUser",
            "username":"sarah",
            "email":"sarah@demo.coopcycle.org",
            "givenName":null,
            "familyName":null,
            "telephone":null,
            "addresses":@array@,
            "roles":[
              "ROLE_COURIER",
              "ROLE_USER"
            ]
          }
        ],
        "hydra:totalItems":1,
        "hydra:view":{
          "@id":"/api/api_users?roles%5B%5D=ROLE_COURIER",
          "@type":"hydra:PartialCollectionView"
        },
        "hydra:search":{
          "@type":"hydra:IriTemplate",
          "hydra:template":"/api/api_users{?roles}",
          "hydra:variableRepresentation":"BasicRepresentation",
          "hydra:mapping":[
            {
              "@type":"IriTemplateMapping",
              "variable":"roles",
              "property":"roles",
              "required":false
            }
          ]
        }
      }
      """
