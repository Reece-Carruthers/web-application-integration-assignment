import useWindowDimensions from "../../hooks/useWindowDimensions.jsx"

/**
 * Pagination component using data from the content API
 * @generated ChatGPT helped with the generation of pagination buttons, mapping over them (generatePageNumbers) and some of the styling for mobile view
 * @author Reece Carruthers (W19011575)
 */
export default function Pagination({ currentPage, totalPages, setPage }) {
    const { width } = useWindowDimensions()
    const getDynamicSurroundingPages = (width) => {
        if (640 >= width) return 1
        return 2
    }

    const generatePageNumbers = () => {
        const dynamicSurroundingPages = getDynamicSurroundingPages(width)
        const pageNumbers = []
        pageNumbers.push(1)

        const ellipsisAfterFirstPage = currentPage > dynamicSurroundingPages + 1
        if (ellipsisAfterFirstPage) {
            pageNumbers.push('...')
        }

        const startPage = ellipsisAfterFirstPage ? currentPage - dynamicSurroundingPages : 2
        const endPage = Math.min(totalPages - 1, currentPage + dynamicSurroundingPages)

        for (let i = startPage; i <= endPage; i++) {
            if (i !== 1 && i !== totalPages) {
                pageNumbers.push(i)
            }
        }

        const ellipsisBeforeLastPage = currentPage < totalPages - dynamicSurroundingPages - 1
        if (ellipsisBeforeLastPage) {
            pageNumbers.push('...')
        }

        if (totalPages !== 1) {
            pageNumbers.push(totalPages)
        }

        return pageNumbers
    }

    const pageNumbers = generatePageNumbers()

    return (
        <div className={`flex flex-col sm:flex-wrap sm:flex-row justify-center items-center mb-4`}>
            {currentPage > 1 && (
                <button className="hover:shadow-xl m-1 p-2 bg-neutral-100 rounded-md drop-shadow-md sm:text-base md:text-lg lg:text-xl 2xl:text-2xl"
                        onClick={() => setPage(currentPage - 1)}>Back</button>
            )}

            <ul className="list-none flex justify-center gap-1">
                {pageNumbers.map((number, index) => (
                    <li key={index} className="sm:text-base md:text-lg lg:text-xl 2xl:text-2xl m-1">
                        {number === '...' ? (
                            <span className="p-2 sm:text-base md:text-lg lg:text-xl 2xl:text-2xl">...</span>
                        ) : (
                            <button className={`sm:text-base md:text-lg lg:text-xl 2xl:text-2xl hover:shadow-xl p-2 bg-neutral-100 rounded-md drop-shadow-md ${currentPage === number ? 'font-bold' : ''}`}
                                    onClick={() => setPage(number)}>
                                {number}
                            </button>
                        )}
                    </li>
                ))}
            </ul>

            {currentPage < totalPages && (
                <button className="sm:text-base md:text-lg lg:text-xl 2xl:text-2xl hover:shadow-xl m-1 p-2 bg-neutral-100 rounded-md drop-shadow-md"
                        onClick={() => setPage(currentPage + 1)}>Next</button>
            )}
        </div>
    )
}

