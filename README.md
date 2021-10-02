# Rokudoku

<p>
A *6 by 6 sudoku* (a *rokudoku*) is defined as a 6 by 6 grid such that the numbers {1, 2, ..., 6} appears on each column, row, and 2 by 3 blocks exactly once. An example of a rokudoku is as follows:

<center>
<img src = img/rokudoku2.png> 
</center>

<p>
Notice that if we *swap* any two numbers $a$, $b$ in a given block, and then modify the other blocks by only swapping the positions of $a$, $b$, we will get  a unique legitimate sudoku. For example, if we swap the position of $1$ and $5$ in the block on the left-upper corner, we will get a new sudouku where each block is different from before:

<center>
<img src = img/rokudoku1.png> 
</center>
      
 <p>
 Our current quest is to solve the following problem: given a 6 by 6 sudoku, what is the set of all sudokus  that are connected to it by a finite number of swaps? In particular, are all the 6 by 6 sudokus related by a finite number of swaps?
 
 <p>
 The main class in the repository uses a tree structure to generate all the unique sudokus related to a given sudoku. 
 
 <p>
 The project is still undergoing at the moment.
