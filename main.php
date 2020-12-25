<?php
    //this class will create a json file of the starwars api database
    class BuildStarWarsJson{
        protected $urls;
        protected $databaseData;
        protected $outputFileName;
        
        public function __construct(){
            $this->initVariables();
            $this->buildDataBase();
            $this->outPutJson();
        }
        
        //initializes the local variables
        private function initVariables(){
            //these are the first pages of the starwars urls
            $this->urls = [
                "planets" => "https://swapi.dev/api/planets/.json?page=1",
                "person" => "https://swapi.dev/api/people/.json?page=1",
                "starShips" => "https://swapi.dev/api/starships/.json?page=1",
                "species" => "https://swapi.dev/api/species/.json?page=1",
                "films" => "https://swapi.dev/api/films/.json?page=1",
                "vehicles" => "https://swapi.dev/api/vehicles/.json?page=1"
                ];
            $this->outputFileName = "starwarsData.json";
            $this->databaseData = [];
        
        
        }
        
        //outputs the json file
        protected function outPutJson(){
            
            file_put_contents($this->outputFileName,json_encode($this->databaseData));
    
    file_put_contents($file,$data);

        }
        
        protected function buildDataBase(){
            //local variables
            $planets = null;   //holds the planets
            $dataSet = [];     //holds the current dataSet retireved from the getData function

            //build planet's data set
            $this->databaseData = $this->extractArray($this->databaseData,$this->getPlanets());
            $planets = $this->databaseData;
            
            //build person's dataSet
            $this->databaseData = $this->extractArray($this->databaseData,$this->getPerson($planets));
            
            //build the species's dataSet
            $this->databaseData = $this->extractArray($this->databaseData,$this->getSpecies($planets));
            
            //build the film's dataSet
            $this->databaseData = $this->extractArray($this->databaseData,$this->getFilms($planets));
            
            //build the vehicle's dataSet
            $this->databaseData = $this->extractArray($this->databaseData,$this->getVehicles($planets));
            
            //build the starShip's dataSet
            $this->databaseData = $this->extractArray($this->databaseData,$this->getStarShips($planets));
            
            print_r(json_encode($this->databaseData));
            
        }
        
        //builds and returns the starShip's dataSet
        protected function getStarShips($planets){
            //local variables
            $dataSet = [];     //holds the current dataSet retireved from the getData function
            $dataSetToReturn = [];

            //add planets dataset
            $dataSet = $this->getData($this->urls['starShips'],$planets);
            $planets = $dataSet;
            
            //build planets
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->name = $dataElement->name;
                $dataObject->model = $dataElement->model;
                $dataObject->cost_in_credits = $dataElement->cost_in_credits;
                $dataObject->length = $dataElement->length;
                $dataObject->crew = $dataElement->crew;
                $dataObject->starship_class = $dataElement->starship_class;
                
                array_push($dataSetToReturn, $dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //builds and returns the vehicles's dataSet
        protected function getVehicles($planets){
            //local variables
            $dataSet = [];     //holds the current dataSet retireved from the getData function
            $dataSetToReturn = [];

            //add planets dataset
            $dataSet = $this->getData($this->urls['vehicles'],$planets);
            $planets = $dataSet;
            
            //build planets
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->name = $dataElement->name;
                $dataObject->model = $dataElement->model;
                $dataObject->manufacturer = $dataElement->manufacturer;
                $dataObject->length = $dataElement->length;
                $dataObject->crew = $dataElement->crew;
                $dataObject->cost_in_credits = $dataElement->cost_in_credits;
                $dataObject->vehicle_class = $dataElement->vehicle_class;
                
                array_push($dataSetToReturn, $dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //builds and returns the film's dataSet
        protected function getFilms($planets){
            //local variables
            $dataSet = [];     //holds the current dataSet retireved from the getData function
            $dataSetToReturn = [];

            //add planets dataset
            $dataSet = $this->getData($this->urls['films'],$planets);
            $planets = $dataSet;
            
            //build planets
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->episode = $dataElement->episode_id;
                $dataObject->name = $dataElement->title;
                $dataObject->director = $dataElement->director;
                $dataObject->producer = $dataElement->producer;
                $dataObject->release_date = $dataElement->release_date;
                
                array_push($dataSetToReturn, $dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //builds and returns the specie's dataSet
        protected function getSpecies($planets){
            //local variables
            $dataSet = [];     //holds the current dataSet retireved from the getData function
            $dataSetToReturn = [];

            //add planets dataset
            $dataSet = $this->getData($this->urls['species'],$planets);
            $planets = $dataSet;
            
            //build planets
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->name = $dataElement->name;
                $dataObject->language = $dataElement->language;
                $dataObject->homeworld = $dataElement->homeworld;
                $dataObject->average_lifespan = $dataElement->average_lifespan;
                $dataObject->average_height = $dataElement->average_height;
                $dataObject->classification = $dataElement->classification;
                $dataObject->designation = $dataElement->designation;
                
                array_push($dataSetToReturn, $dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //builds and returns the planets dataSet
        protected function getPlanets(){
            //local variables
            $planets = null;   //holds the planets
            $dataSet = [];     //holds the current dataSet retireved from the getData function
            $dataSetToReturn = [];

            //add planets dataset
            $dataSet = $this->getData($this->urls['planets'],$planets);
            $planets = $dataSet;
            
            //build planets
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->name = $dataElement->name;
                $dataObject->rotation_period = $dataElement->rotation_period;
                $dataObject->orbital_period = $dataElement->orbital_period;
                $dataObject->diameter = $dataElement->diameter;
                $dataObject->climate = $dataElement->climate;
                $dataObject->gravity = $dataElement->gravity;
                $dataObject->terrain = $dataElement->terrain;
                $dataObject->surface_water = $dataElement->surface_water;
                $dataObject->population = $dataElement->population;
                
                array_push($dataSetToReturn, $dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //builds the person's dataset
        protected function getPerson($planets){
            //local variables
            $dataSet = [];     //holds the current dataSet 
            $dataSetToReturn = [];
            
            //add person's dataSet
            $dataSet = $this->getData($this->urls['person'],$planets);
            
            //build persons
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->name = $dataElement->name;
                $dataObject->height = $dataElement->height;
                $dataObject->mass = $dataElement->mass;
                $dataObject->birth_year = $dataElement->birth_year;
                $dataObject->gender = $dataElement->gender;
                $dataObject->homeworld = $dataElement->homeworld;
                
                array_push($dataSetToReturn,$dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //gets the data for the passedURL
        protected function getData($dataURL,$planets){
            //local variables
            $firstSet = json_decode(file_get_contents($dataURL));
            $secondSet;
            $mergedArray = $firstSet->results;

            if(isset($firstSet->next)){
                $secondSet = json_decode(file_get_contents($firstSet->next));
                $mergedArray = array_merge($firstSet->results,$secondSet->results);
            }

            //continue to add to the mergedArray as long as new data is found
            while(isset($secondSet->next)){
                $secondSet = json_decode(file_get_contents($secondSet->next));

                $mergedArray = array_merge($mergedArray,$secondSet->results); 
            }
            
            //check if homeworld exists and needs to be set
            $mergedArray = $this->checkAndSetHomeworld($mergedArray,$planets);

            //return the data
            return $mergedArray;
        }
        
        //extracts the data from the second argument array and places it as individual data in the first argument
        protected function extractArray($mainArray,$extractArray){
            
            foreach($extractArray as $element){
                array_push($mainArray,$element);
            }
            
            return $mainArray;
        }
        
        //checks the input for a homeworld, and sets the correct homeworld if exists
        protected function checkAndSetHomeworld($mergedArray,$planets){
            
            
            //set the correct homeworlds by using the planets
            foreach($mergedArray as $dataElement){
                if(isset($dataElement->homeworld)){
                    $regExp = "/\d+/i";
                    $homeworld; //Holds the index of the homeworld

                    preg_match_all($regExp,$dataElement->homeworld,$homeworld);
                    $homeworld = $homeworld[0][0] - 1; //Correct index of the homeworld

                    $homeworld = $planets[$homeworld]->name;
                    $dataElement->homeworld = $homeworld;
                }else{
                    $dataElement->homeworld = 'n/a';
                }
            }

            return $mergedArray;
        }
        
    }

    new BuildStarWarsJson();
?>