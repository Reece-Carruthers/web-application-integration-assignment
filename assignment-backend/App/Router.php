<?php

namespace App;

use App\EndpointControllers\AuthController;
use App\EndpointControllers\AuthorAffiliationController;
use App\EndpointControllers\ContentController;
use App\EndpointControllers\CountryController;
use App\EndpointControllers\FavouriteController;
use App\EndpointControllers\NotesController;
use App\EndpointControllers\PreviewController;
use App\EndpointGateways\AuthGateway;
use App\EndpointGateways\AuthorAffiliationGateway;
use App\EndpointGateways\ContentGateway;
use App\EndpointGateways\CountryGateway;
use App\EndpointGateways\FavouriteGateway;
use App\EndpointGateways\NotesGateway;
use App\EndpointGateways\PreviewGateway;

/**
 * A class for handling all the routing to the correct endpoint controllers
 *
 * @author Reece Carruthers (W19011575)
 */
abstract class Router
{
    /**
     * Processes the request and returns the data as a array
     * @param Database $conferenceDatabase
     * @param Database $usersDatabase
     * @return array
     */
    public static function processRequest(Database $conferenceDatabase, Database $usersDatabase): array
    {
        try {
            switch (Request::endpointName()) {
                /**
                 * Endpoint 1
                 */
                case '/':
                case '/developer':
                case '/developer/':
                    $data = [
                        "name" => "Reece Carruthers",
                        "code" => "W19011575"
                    ];
                    break;
                /**
                 * Endpoint 2
                 */
                case '/country':
                case '/country/':
                    $validParameters = ['GET' => null];

                    $countryGateway = new CountryGateway($conferenceDatabase);
                    $countryController = new CountryController($countryGateway, $validParameters);
                    $countryController->processRequest(Request::method());

                    $data = $countryController->getData();
                    break;
                /**
                 * Endpoint 3
                 */
                case '/preview':
                case '/preview/':
                    $validParameters = ['GET' => ['limit']];

                    $previewGateway = new PreviewGateway($conferenceDatabase);
                    $previewController = new PreviewController($previewGateway, $validParameters);
                    $previewController->processRequest(Request::method(), Request::params());

                    $data = $previewController->getData();
                    break;
                /**
                 * Endpoint 4
                 */
                case '/author-affiliation':
                case '/author-affiliation/':
                    $validParameters = ['GET' => ['contentID', 'type']];

                    $authorAffiliationGateway = new AuthorAffiliationGateway($conferenceDatabase);
                    $authorAffiliationController = new AuthorAffiliationController($authorAffiliationGateway, $validParameters);
                    $authorAffiliationController->processRequest(Request::method(), Request::params());

                    $data = $authorAffiliationController->getData();
                    break;
                /**
                 * Endpoint 5
                 */
                case '/content':
                case '/content/':
                    $validParameters = ['GET' => ['page', 'type', 'contentID']];

                    $contentGateway = new ContentGateway($conferenceDatabase);
                    $contentController = new ContentController($contentGateway, $validParameters);
                    $contentController->processRequest(Request::method(), Request::params());

                    $data = $contentController->getData();
                    break;
                /**
                 * Endpoint 6
                 */
                case '/token':
                case '/token/':
                    $validParameters = ['GET' => null];

                    $authGateway = new AuthGateway($usersDatabase);
                    $authController = new AuthController($authGateway, $validParameters);
                    $authController->processRequest(Request::method());

                    $data = $authController->getData();
                    break;
                /**
                 * Endpoint 7
                 */
                case '/notes':
                case '/notes/':
                    $validParameters = [
                        'GET' => ['contentID'],
                        'POST' => ['contentID', 'note'],
                        'PUT' => ['noteId', 'note'],
                        'DELETE' => ['noteId']
                    ];

                    $notesGateway = new NotesGateway($usersDatabase);
                    $notesController = new NotesController($notesGateway, $validParameters);
                    $notesController->processRequest(Request::method(), Request::params());

                    $data = $notesController->getData();
                    break;
                /**
                 * Additional endpoint to favourite a piece of content
                 */
                case '/favourite':
                case '/favourite/':
                    $validParameters = [
                        'GET' => ['contentID'],
                        'POST' => ['contentID'],
                        'DELETE' => ['contentID']
                    ];

                    $favouriteGateway = new FavouriteGateway($usersDatabase);
                    $favouriteController = new FavouriteController($favouriteGateway, $validParameters);
                    $favouriteController->processRequest(Request::method(), Request::params());

                    $data = $favouriteController->getData();
                    break;
                default:
                    throw new ClientError(404);
            }
        } catch (ClientError $e) {
            $data = ['message' => $e->getMessage()];
        }

        return $data;
    }
}