# KF6012 - Web Application Integration
## This API was developed by Reece Carruthers (W19011575)


All endpoints will start with the URL: https://w19011575.nuwebspace.co.uk/assignment/api/ and all return JSON data
The frontend application is deployed on https://w19011575.nuwebspace.co.uk/assignment-frontend/


## [Endpoints](https://w19011575.nuwebspace.co.uk/assignment/api/)

### **Endpoint 1: Developer**

## `GET https://w19011575.nuwebspace.co.uk/assignment/api/`
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/developer`

### Successful response

#### 200 OK

* A call is made to the `GET` endpoint

* The endpoint will return a JSON response with the developer's name and code


#### Sample response

```json
{
    "name": "Reece Carruthers",
    "code": "W19011575"
}
```

### **Endpoint 2: Country**
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/country`

### Successful response

#### 200 OK

* A call is made to the `GET` endpoint
* The endpoint will return a JSON response with a list of all the countries relating to the conference

#### 405 Method Not Allowed

* Incorrect HTTP method was passed to the endpoint e.g POST


#### Sample response

```json
{
    "data": [
        {
            "country": "Australia"
        },
        {
            "country": "United States"
        }
    ]
}
```

### **Endpoint 3: Preview**
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/preview?limit=[Option[Int]]`

#### Params

* Optional param list for endpoint 3
    * limit - Sets the maximum number of preview videos to be returned
### Successful response

#### 200 OK

* A call is made to the `GET` endpoint
* The endpoint will return a JSON response with a list of all the titles of research which have preview videos attached in random order, additionally the endpoint will return the ID of the content.

### Unsuccessful responses (with possible causes)

#### 405 Method Not Allowed

* Incorrect HTTP method was passed to the endpoint e.g POST

#### 422 Unprocessable Entity

* Limit parameter was passed with the incorrect data type or semantic errors

#### Sample response

```json
{
    "data": [
        {
            "content_id": 96238,
            "title": "It Made Me Feel So Much More at Home Here: Patient Perspectives on Smart Home Technology Deployed at Scale in a Rehabilitation Hospital",
            "preview_video": "https://www.youtube.com/watch?v=EVAXd7ipvFw"
        },
        {
            "content_id": 96518,
            "title": "Physically Situated Tools for Exploring a Grain Space in Computational Machine Knitting",
            "preview_video": "https://www.youtube.com/watch?v=VJaTztiGT80"
        }
    ]
}
```

### **Endpoint 4: Author and Affiliation**
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/author-and-affiliation?contentID=[Option[Int]]`
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/author-and-affiliation?country=[Option[String]]`

#### Params

* Optional param list for endpoint 4
    * contentID - Returns all relating rows with the specified content ID
    * country - Returns all relating rows with the specified country (case sensitive)

Note - You can only use contentID parameter by itself and not with the country parameter.
### Successful response

#### 200 OK

* A call is made to the `GET` endpoint
* The endpoint will return a JSON response with a list of associated research each author is related with
* The country parameter was passed and resulted in no data being found

### Unsuccessful responses (with possible causes)

#### 404 Resource Not Found

* The contentID parameter was passed and resulted in no data being found

#### 405 Method Not Allowed

* Incorrect HTTP method was passed to the endpoint e.g POST

#### 422 Unprocessable Entity

* Parameters were passed with the incorrect data types or semantic errors

#### Sample response

* `GET` operation with no parameters
```json
{
    "data": [
        {
            "author_id": 91994,
            "author_name": "Devansh  Saxena",
            "content_id": 96102,
            "content_title": "Rethinking \"Risk\" in Algorithmic Systems Through A Computational Narrative Analysis of Casenotes in Child Welfare",
            "affiliation_country": "United States",
            "affiliation_city": "Milwaukee",
            "affiliation_institution": "Marquette University"
        },
        {
            "author_id": 91995,
            "author_name": "Ioanna  Lykourentzou",
            "content_id": 96540,
            "content_title": "Tasks of a Different Color: How Crowdsourcing Practices Differ per Complex Task Type and Why This Matters",
            "affiliation_country": "Netherlands",
            "affiliation_city": "Utrecht",
            "affiliation_institution": "Utrecht University"
        }
   ]
}
```
* `GET` operation with a incorrect type parameter, resulting in no data being found
```json
{
    "data": []
}
```

### **Endpoint 5: Content**
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/content?page=[Option[Int]]&type=[Option[String]]`
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/content?contentID=[Option[Int]]`

#### Params

* Optional param list for endpoint 5
  * page - Returns a JSON response with 20 records, each page offsets the result by 20 e.g page 1 has the first 20, page 2 has the next 20.
  * type - Returns all relating rows with the specified type
  * type=listTypes - Returns a JSON response with a list of all the types of content
  * contentID - Returns all relating rows with the specified content ID, can only be used by itself
* Note - The award_name value will be null if the associated content has no award, if it has an award the award_name value will be the name of the award.

### Successful response

#### 200 OK

* A call is made to the `GET` endpoint
* The endpoint will return a JSON response with a list of research content includes the title, award_name (null if it does not have a reward), abstract (can be null) and type.
* The page or type parameter resulted in no data being found, so a empty data array is sent back along with pagination information.
### Unsuccessful responses (with possible causes)

#### 404 Resource Not Found

* The contentID parameter was passed and resulted in no data being found

#### 405 Method Not Allowed

* Incorrect HTTP method was passed to the endpoint e.g POST

#### 422 Unprocessable Entity

* Parameters were passed with the incorrect data types or semantic errors
* Incorrect combination of parameters were passed

#### Sample response

* 'GET' request without contentID parameter
```json
{
    "data": [
        {
            "content_id": 95692,
            "content_title": "Older Adults Using Technology for Meaningful Activities During COVID-19: An Analysis Through the Lens of Self-Determination Theory",
            "content_abstract": "Restrictions during the COVID-19 pandemic significantly affected people's opportunities to engage in activities that are meaningful to their lives. In response to these constraints, many people, including older adults, turned to digital technologies as alternative ways to pursue meaningful activities. These technology-mediated activities, however, presented new challenges for older adults' everyday use of technology. In this paper, we investigate how older adults used digital technologies for meaningful activities during COVID-19 restrictions. We conducted in-depth interviews with 40 older adults and analyzed the interview data through the lens of self-determination theory (SDT). Our analysis shows that using digital technologies for meaningful activities can both support and undermine older people's three basic psychological needs for autonomy, competence, and relatedness. We argue that future technologies should be designed to empower older adults' content creation, engagement in personal interests, exploration of technology, effortful communication, and participation in beneficent activities.",
            "content_type": "Paper",
            "award_name": null
        },
        {
            "content_id": 95693,
            "content_title": "VizProg: Identifying Misunderstandings by Visualizing Students' Coding Progress",
            "content_abstract": "Programming instructors often conduct in-class exercises to help them identify students that are falling behind and surface students' misconceptions. However, as we found in interviews with programming instructors, monitoring students' progress during exercises is difficult, particularly for large classes. We present VizProg, a system that allows instructors to monitor and inspect students' coding progress in real-time during in-class exercises. VizProg represents students' statuses as a 2D Euclidean spatial map that encodes the students' problem-solving approaches and progress in real-time. VizProg allows instructors to navigate the temporal and structural evolution of students' code, understand relationships between code, and determine when to provide feedback. A comparison experiment showed that VizProg helped to identify more students' problems than a baseline system. VizProg also provides richer and more comprehensive information for identifying important student behavior. By managing students' activities at scale, this work presents a new paradigm for improving the quality of live learning.",
            "content_type": "Paper",
            "award_name": "Honourable mention"
        }
    ],
    "pagination": {
        "total": 1544,
        "pages": 78
    }
}
```
* 'GET' request with contentID parameter
```json
{
  "data": [
    {
      "content_id": 95692,
      "content_title": "Older Adults Using Technology for Meaningful Activities During COVID-19: An Analysis Through the Lens of Self-Determination Theory",
      "content_abstract": "Restrictions during the COVID-19 pandemic significantly affected people's opportunities to engage in activities that are meaningful to their lives. In response to these constraints, many people, including older adults, turned to digital technologies as alternative ways to pursue meaningful activities. These technology-mediated activities, however, presented new challenges for older adults' everyday use of technology. In this paper, we investigate how older adults used digital technologies for meaningful activities during COVID-19 restrictions. We conducted in-depth interviews with 40 older adults and analyzed the interview data through the lens of self-determination theory (SDT). Our analysis shows that using digital technologies for meaningful activities can both support and undermine older people's three basic psychological needs for autonomy, competence, and relatedness. We argue that future technologies should be designed to empower older adults' content creation, engagement in personal interests, exploration of technology, effortful communication, and participation in beneficent activities.",
      "content_type": "Paper",
      "award_name": null
    }
  ]
}
```
* 'GET' request with the type=listTypes parameter 
```json
{
  "data": [
    {
      "name": "Course"
    },
    {
      "name": "Demo"
    },
    {
      "name": "Doctoral Consortium"
    },
    {
      "name": "Event"
    },
    {
      "name": "Late-Breaking Work"
    },
    {
      "name": "Paper"
    },
    {
      "name": "Poster"
    },
    {
      "name": "Work-in-Progress"
    },
    {
      "name": "Workshop"
    },
    {
      "name": "Case Study"
    },
    {
      "name": "Panel"
    },
    {
      "name": "AltCHI"
    },
    {
      "name": "SIG"
    },
    {
      "name": "Keynote"
    },
    {
      "name": "Interactivity"
    },
    {
      "name": "Journal"
    },
    {
      "name": "Symposia"
    },
    {
      "name": "Competitions"
    }
  ]
}
```
* 'GET' request with type/page parameter resulting in no data being found
```json
{
  "data": [],
  "pagination": {
    "total": 0,
    "pages": 0
  }
}
```

### **Endpoint 6: Token**
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/token`
## `POST https://w19011575.nuwebspace.co.uk/assignment/api/token`

#### Headers

* A email and password must be passed via the Authorisation header in the format of `email:password`

### Successful response

#### 200 OK

* A call is made to the `GET` endpoint
* A call is made to the `POST` endpoint
* The endpoint will return a JSON response with a bearer token valid for 30 minutes.

### Unsuccessful responses (with possible causes)

#### 401 Unauthorized

* Incorrect email or password were passed to the endpoint

#### 405 Method Not Allowed

* Incorrect HTTP method was passed to the endpoint e.g DELETE, PUT


#### Sample response

```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwiaWF0IjoxNzAzMDg2MjcwLCJleHAiOjE3MDMwODgwNzAsImlzcyI6IncxOTAxMTU3NSJ9.x0sZtJ3rq9L-foN2Jo_gJHCS8DR1WLmUPc_6StFivYM"
}
```

### **Endpoint 7: Notes**
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/notes?contentID=[Option[Int]]`
## `POST https://w19011575.nuwebspace.co.uk/assignment/api/notes?contentID=[Int]&note=[String(1500)]`
## `PUT https://w19011575.nuwebspace.co.uk/assignment/api/notes?noteId=[Int]&note=[String(1500)]`
## `DELETE https://w19011575.nuwebspace.co.uk/assignment/api/notes?noteId=[Int]`

#### Headers

* A valid bearer token must be passed to the endpoint via the Authorisation header, a valid bearer token can be obtained from the token endpoint

### Successful response

#### 200 OK

* A call is made to the `GET` endpoint
* * The endpoint will return a JSON response with a list of notes relating to the content ID/user depending on parameters passed to the endpoint
* * If no data is found, the endpoint will return a empty data array
* * A call is made to the `POST` endpoint
* * The endpoint will return the JSON of the row that has been added
* A call is made to the `PUT` endpoint
* * The endpoint will return the JSON of the row that has been updated

#### 201 Created
* A call is made to the `POST` endpoint
* * The endpoint will return the JSON of the new row that has been added

#### 204 Entity No Content

* A call is made to the `DELETE` endpoint
* * The endpoint will return a empty JSON response on successful deletion

### Unsuccessful responses (with possible causes)

#### 401 Unauthorized

* Invalid bearer token was passed to the endpoint e.g expired

#### 404 Not Found

* On a PUT request if the noteID does not exist, the request will fail with a 404.
* On a DELETE request if the noteID does not exist, the request will fail with a 404.

#### 405 Method Not Allowed

* Incorrect HTTP method was passed to the endpoint

#### 422 Unprocessable Entity

* Parameters were passed with the incorrect data types or semantic errors
* Invalid parameters were passed for a specific request method

#### 500 Internal Server Error
* POST failed due to a database error


#### Sample response

```json
{
    "data": [
        {
            "note_id": 1,
            "content_id": 95692,
            "note_text": "Test note 123",
            "created_at": "2023-12-19 14:18:59",
            "updated_at": "2023-12-19 14:18:59"
        },
        {
            "note_id": 2,
            "content_id": 95693,
            "note_text": "Another test note",
            "created_at": "2023-12-19 14:30:49",
            "updated_at": "2023-12-19 14:30:49"
        }
    ]
}
```

### Additional Endpoint

### **Favourite**
## `GET https://w19011575.nuwebspace.co.uk/assignment/api/favourite?contentID=[Option[Int]]`
## `POST https://w19011575.nuwebspace.co.uk/assignment/api/favourite?contentID=[Int]`
## `DELETE https://w19011575.nuwebspace.co.uk/assignment/api/favourite?contentID=[Int]`

#### Headers

* A valid bearer token must be passed to the endpoint via the Authorisation header, a valid bearer token can be obtained from the token endpoint

### Successful response

#### 200 OK

* A call is made to the `GET` endpoint without a contentID parameter
* * The endpoint will return a JSON response with a list of favourites the user has selected or an empty data array if they have none

* A call is made to the `GET` endpoint with a contentID parameter
* * The endpoint will return a JSON response telling the user if the content ID is a favourite or not - this is based on if the content ID is in the favourites table

#### 201 Created
* A call is made to the `POST` endpoint
* * The endpoint will return the JSON of the new row that has been added

#### 204 Entity No Content

* A call is made to the `DELETE` endpoint
* * The endpoint will return a empty JSON response on successful deletion

### Unsuccessful responses (with possible causes)

#### 401 Unauthorized

* Invalid bearer token was passed to the endpoint e.g expired

#### 404 Not Found

* DELETE request failed due to the contentID not being in the favourites table

#### 405 Method Not Allowed

* Incorrect HTTP method were passed to the endpoint

#### 422 Unprocessable Entity

* Parameters were passed with the incorrect data types
* Invalid parameters were passed for a specific request method

#### 500 Internal Server Error
* POST failed due to a database error


#### Sample response

* A call is made to the `GET` endpoint without a contentID parameter
```json
{
    "data": [
        {
            "content_id": 95692
        },
        {
            "content_id": 95693
        }
    ]
}
```
* A call is made to the `GET` endpoint with a contentID parameter
```json
{
    "data": {
        "isFavourite": true
    }
}
```