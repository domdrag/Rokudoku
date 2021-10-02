# Rokudoku

A *6 by 6 sudoku* (a *rokudoku*) is defined as a 6 by 6 grid such that the numbers {1, 2, ..., 6} appears on each column, row, and 2 by 3 blocks exactly once. An example of a rokudoku is as follows:

<img src = doc/rokudoku2.png> 
</center>

Notice that if we *swap* any two numbers **a**, *b* in a given block, and then modify the other blocks by only swapping the positions of *a*, *b*, we will get a unique legitimate sudoku. For example, if we swap the position of *1* and *5* in the block on the left-upper corner, we will get a new sudouku where each block is different from before:

<center>
<img src = doc/rokudoku1.png> 
</center>
      

