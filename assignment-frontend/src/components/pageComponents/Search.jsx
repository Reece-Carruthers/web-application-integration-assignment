/**
 *
 * @param currentSearch
 * @param handleSearch
 * @returns {JSX.Element}
 * @generated Had GitHub CoPilot help with setting up the input box
 * @author Reece Carruthers
 */
export default function Search({currentSearch, handleSearch}) {
    return (
        <>
            <div className="flex flex-col lg:flex-row justify-center items-center">
                <div className="flex flex-col lg:flex-row justify-center items-center">
                    <label htmlFor="search" className="sr-only">Search</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Search"
                        className="rounded-md border-2 border-neutral-100 p-2 m-2 sm:text-base md:text-lg lg:text-xl 2xl:text-2xl"
                        value={currentSearch}
                        onChange={(e) => handleSearch(e.target.value)}
                    />
                </div>
            </div>
        </>
    )
}