{
  "firstStep": "35f3cbba-6c95-43cc-91ae-d489db1113cf",
  "steps": {
    "35f3cbba-6c95-43cc-91ae-d489db1113cf": {
      "id": "35f3cbba-6c95-43cc-91ae-d489db1113cf",
      "action": "VISIT",
      "title": null,
      "tags": [],
      "field": null,
      "value": "https://demo.dexi.io/",
      "next": [
        "cc1f0cd8-ae19-468c-b4c7-847536d853c0"
      ],
      "branchMode": "ALL",
      "errorMode": "REPORT_HERE",
      "errorMessage": null,
      "groupId": null,
      "snippetId": null,
      "snippetStepId": null,
      "waitAfter": -1,
      "timeout": -1,
      "formatters": [],
      "options": {}
    },
    "53224927-324b-46d7-bbdd-139246513f6c": {
      "id": "53224927-324b-46d7-bbdd-139246513f6c",
      "action": "FLUSH",
      "title": null,
      "tags": [],
      "field": null,
      "value": null,
      "next": [],
      "branchMode": "ALL",
      "errorMode": "REPORT_HERE",
      "errorMessage": null,
      "groupId": null,
      "snippetId": null,
      "snippetStepId": null,
      "waitAfter": -1,
      "timeout": -1,
      "formatters": [],
      "options": {}
    },
    "cc1f0cd8-ae19-468c-b4c7-847536d853c0": {
      "id": "cc1f0cd8-ae19-468c-b4c7-847536d853c0",
      "action": "IN2OUT",
      "title": null,
      "tags": [],
      "field": "input_1",
      "value": "output_1",
      "next": [
        "53224927-324b-46d7-bbdd-139246513f6c"
      ],
      "branchMode": "ALL",
      "errorMode": "REPORT_HERE",
      "errorMessage": null,
      "groupId": null,
      "snippetId": null,
      "snippetStepId": null,
      "waitAfter": -1,
      "timeout": -1,
      "formatters": [],
      "options": {}
    }
  },
  "functions": {},
  "networkFilters": [],
  "javascriptEnabled": true,
  "autoLoadImages": true,
  "stylesheetsEnabled": true,
  "forceSinglePageNavigation": false,
  "formatter": null,
  "groupedOutput": false,
  "stepBreak": 0,
  "javascriptInjection": null,
  "userAgent": "Auto",
  "sslProtocol": "tls1.0",
  "categoryId": null,
  "type": "SCRAPER",
  "editorVersion": 2,
  "hidden": false,
  "proxies": [],
  "output": {
    "output_1": {
      "id": "output_1",
      "uuid": "0f2b5fcf-8a41-4d5b-92af-fb0a88d8b3c1",
      "description": null,
      "type": "string",
      "title": null,
      "order": 0,
      "unique": false,
      "indexed": true,
      "required": false,
      "editable": true,
      "defaultValue": null,
      "options": null,
      "checksum": "caff4827f65927dee779695d80e2a9a8",
      "items": null,
      "properties": null
    }
  },
  "outputDataType": null,
  "useOutputDataType": false,
  "input": {
    "input_1": {
      "id": "input_1",
      "uuid": "49a065b6-55fa-4fe9-bd04-daee126a26da",
      "description": null,
      "type": "string",
      "title": null,
      "order": 0,
      "unique": false,
      "indexed": true,
      "required": false,
      "editable": true,
      "defaultValue": null,
      "options": null,
      "checksum": "fd8a3ff122a80c7ed787b323894bc7d9",
      "items": null,
      "properties": null
    }
  },
  "inputDataType": null,
  "useInputDataType": false,
  "testInput": {},
  "parentRobot": null,
  "fixedHeaders": {},
  "tags": [],
  "deleted": false
}