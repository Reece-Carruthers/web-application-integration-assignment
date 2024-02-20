import {useFetchData} from "../../hooks/useFetchData.jsx"
import {useState} from "react"
import Search from "../pageComponents/Search.jsx"
import Message from "../pageComponents/Message.jsx";

/**
 * A page to display a list of countries from the conference API
 * @returns {JSX.Element}
 * @generated - used ChatGPT to refactor and clean-up the code
 * @author Reece Carruthers (W19011575)
 */
export default function CountriesPage() {
    const { data, isLoading, isError } = useFetchData('https://w19011575.nuwebspace.co.uk/assignment/api/country/', null)
    const [search, setSearch] = useState("")

    const handleSearch = (searchValue) => {
        setSearch(searchValue)
    }

    const filteredCountries = data?.data?.filter(country =>
        country.country?.toLowerCase().includes(search.toLowerCase())
    ) || []

    return (
        <div rel="Image by rawpixel.com on Freepik (http://www.freepik.com)" className="bg-[url('/src/assets/background.jpg')] min-h-screen bg-cover overflow-auto">
            {isLoading && <Message message="Loading countries..."/>}
            {isError && <Message message="There was an error loading the countries"/>}
            {!isError && data?.data && <Search currentSearch={search} handleSearch={handleSearch}/>}

            <CountriesList countries={filteredCountries} />
        </div>
    )
}

const CountriesList = ({ countries }) => {
    if (countries.length === 0) {
        return <Message message="No matching country found"/>
    }

    return (
        <ul className="flex justify-evenly flex-wrap">
            {countries.map((country, index) => (
                <li key={index} className="bg-neutral-100 m-2 p-4 lg:m-4 lg:p-6 lg:text-2xl rounded-xl drop-shadow-md">
                    {country.country}
                </li>
            ))}
        </ul>
    )
}