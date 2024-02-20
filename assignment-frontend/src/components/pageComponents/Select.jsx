/**
 * A select component
 * @param selectOptions
 * @param defaultOption
 * @param setSelection
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575)
 * @generated using GitHub CoPilot
 */
export default function Select({selectOptions, defaultOption, setSelection}) {
    return (
        <>
            <div className="flex flex-col lg:flex-row justify-center items-center">
                <div className="flex flex-col lg:flex-row justify-center items-center">
                    <label htmlFor="search" className="sr-only">Search</label>
                    <select
                        id="search"
                        name="search"
                        className="rounded-md border-2 border-neutral-100 p-2 m-2 sm:text-base md:text-lg lg:text-xl 2xl:text-2xl"
                        onChange={(e) => setSelection(e.target.value)}
                    >
                        <option value="">{defaultOption}</option>
                        {selectOptions.map((option, index) => (
                            <option key={index} value={option}>{option}</option>
                        ))}
                    </select>
                </div>
            </div>
        </>
    )
}